<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Router
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Router;

/**
 * Router class.
 *
 * @package    phpOMS\Router
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Router
{

    /**
     * Routes.
     *
     * @var array
     * @since 1.0.0
     */
    private $routes = [];

    /**
     * Add routes from file.
     *
     * Files need to return a php array of the following structure:
     * return [
     *      '{REGEX_PATH}' => [
     *          'dest' => '{DESTINATION_NAMESPACE:method}', // can also be static by using :: between namespace and functio name
     *          'verb' => RouteVerb::{VERB},
     *          'permission' => [ // optional
     *              'module' => '{MODULE_NAME}',
     *              'type' => PermissionType::{TYPE},
     *              'state' => PermissionState::{STATE},
     *          ],
     *          // define different destination for different verb
     *      ],
     *      // define another regex path, destination, permission here
     * ];
     *
     * @param string $path Route file path
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function importFromFile(string $path) : bool
    {
        if (!\file_exists($path)) {
            return false;
        }

        /** @noinspection PhpIncludeInspection */
        $this->routes += include $path;

        return true;
    }

    /**
     * Add route.
     *
     * @param string $route       Route regex
     * @param mixed  $destination Destination e.g. Module:function string or callback
     * @param int    $verb        Request verb
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function add(string $route, $destination, int $verb = RouteVerb::GET) : void
    {
        if (!isset($this->routes[$route])) {
            $this->routes[$route] = [];
        }

        $this->routes[$route][] = [
            'dest' => $destination,
            'verb' => $verb,
        ];
    }

    /**
     * Route request.
     *
     * @param string $request Request to route
     * @param int    $verb    Route verb
     *
     * @return array[]
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function route(string $request, int $verb = RouteVerb::GET, string $app = null, int $orgId = null, $account = null) : array
    {
        $bound = [];
        foreach ($this->routes as $route => $destination) {
            foreach ($destination as $d) {
                if ($this->match($route, $d['verb'], $request, $verb)) {
                    // if csrf is required but not set
                    if (isset($d['csrf']) && !$d['csrf']) {
                        \array_merge($bound, $this->route('/' . $app . '/e403', $verb));

                        continue;
                    }

                    // if permission check is invalid
                    if ((isset($d['permission']) && $account === null)
                        || (isset($d['permission'])
                            && !$account->hasPermission($d['permission']['type'], $orgId, $app, $d['permission']['module'], $d['permission']['state']))
                    ) {
                        \array_merge($bound, $this->route('/' . $app . '/e403', $verb));

                        continue;
                    }

                    $bound[] = ['dest' => $d['dest']];
                }
            }
        }

        return $bound;
    }

    /**
     * Match route and uri.
     *
     * @param string $route      Route
     * @param int    $routeVerb  GET,POST for this route
     * @param string $uri        Uri
     * @param int    $remoteVerb Verb this request is using
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function match(string $route, int $routeVerb, string $uri, int $remoteVerb = RouteVerb::GET) : bool
    {
        return (bool) \preg_match('~^' . $route . '$~', $uri) && ($routeVerb === RouteVerb::ANY || $remoteVerb === RouteVerb::ANY || ($remoteVerb & $routeVerb) === $remoteVerb);
    }
}
