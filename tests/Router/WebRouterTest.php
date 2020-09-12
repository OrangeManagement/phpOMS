<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Router;

use Modules\Admin\Controller\BackendController;
use Modules\Admin\Models\PermissionState;
use phpOMS\Account\Account;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Autoloader;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Router\RouteVerb;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Router\WebRouterTest: Router for web requests
 *
 * @internal
 */
class WebRouterTest extends \PHPUnit\Framework\TestCase
{
    protected WebRouter $router;

    protected function setUp() : void
    {
        $this->router = new WebRouter();
    }

    /**
     * @testdox The route result for an empty request is empty
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEmpty(
            $this->router->route(
                (new HttpRequest(new HttpUri('')))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox A none-existing routing file cannot be imported
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testInvalidRoutingFile() : void
    {
        self::assertFalse($this->router->importFromFile(__DIR__ . '/invalidFile.php'));
    }

    /**
     * @testdox A existing routing file can be imported
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testLoadingRoutesFromFile() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));
    }

    /**
     * @testdox A matching route returns the destinations
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox Invalid routing verbs don't match even if the route matches
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testRouteMissMatchingForInvalidVerbs() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::PUT)
        );
    }

    /**
     * @testdox Routes can be added dynamically
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testDynamicRouteAdding() : void
    {
        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );

        $this->router->add('^.*/backends/admin/settings/general.*$', 'Controller:test', RouteVerb::GET | RouteVerb::SET);
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::ANY)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::SET)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')))->getUri()->getRoute(), null, RouteVerb::GET)
        );
    }

    /**
     * @testdox Routes which require a CSRF token can only match with a CSRF token
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testWithCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewCsrf']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute(),
                'csrf_string'
            )
        );
    }

    /**
     * @testdox Routes which require a CSRF token don't match without a CSRF token
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testWithoutCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox Routes only match if the permissions match
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testWithValidPermissions() : void
    {
        if (!Autoloader::exists('\Modules\Admin\Controller\Controller')) {
            self::markTestSkipped();
        }

        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFilePermission.php'));

        $perm = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account = new Account();
        $account->addPermission($perm);

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(new HttpUri('http://test.com/backend/admin/settings/general/something?test')))->getUri()->getRoute(),
                null,
                RouteVerb::GET,
                null,
                null,
                $account
            )
        );
    }

    /**
     * @testdox Routes don't match if the permissions don't match
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testWithInvalidPermissions() : void
    {
        if (!Autoloader::exists('\Modules\Admin\Controller\Controller')) {
            self::markTestSkipped();
        }

        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFilePermission.php'));

        $perm2 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::CREATE
        ) extends PermissionAbstract {};

        $perm3 = new class(
            null,
            null,
            'InvalidModule',
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $perm4 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            99,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account2 = new Account();
        $account2->addPermission($perm2);
        $account2->addPermission($perm3);
        $account2->addPermission($perm4);

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(new HttpUri('http://test.com/backend/admin/settings/general/something?test')))->getUri()->getRoute(),
                null,
                RouteVerb::GET,
                null,
                null,
                $account2
            )
        );
    }

    /**
     * @testdox A data validation pattern validates matches correctly
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testDataValidation() : void
    {
        $this->router->add(
            '^.*/backends/admin/settings/general.*$',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::ANY, null, null, null, ['test_pattern' => 'abcdef'])
        );
    }

    /**
     * @testdox A data validation pattern invalidates missmatches
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testInvalidDataValidation() : void
    {
        $this->router->add(
            '^.*/backends/admin/settings/general.*$',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertNotEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::ANY, null, null, null, ['test_pattern' => '123'])
        );
    }

    /**
     * @testdox A uri can be used for data population
     * @covers phpOMS\Router\WebRouter
     * @group framework
     */
    public function testDataFromPattern() : void
    {
        $this->router->add(
            '^.*/backends/admin.*$',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            [],
            '/^.*?(something)=(\d*).*?$/'
        );

        self::assertEquals(
            [[
                'dest' => 'Controller:test',
                'data' => [
                    '/backends/admin?something=123&sd=asdf',
                    'something',
                    '123',
                ]
            ]],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin?something=123&sd=asdf')
                ))->getUri()->getRoute(), null, RouteVerb::ANY)
        );
    }
}
