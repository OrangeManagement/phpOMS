<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Views;

use phpOMS\ApplicationAbstract;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Localization\Localization;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\System\FilePathException;

/**
 * List view.
 *
 * @category   Theme
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class View implements RenderableInterface
{

    /**
     * Template.
     *
     * @var \string
     * @since 1.0.0
     */
    protected $template = null;

    /**
     * Views.
     *
     * @var \phpOMS\Views\View[]
     * @since 1.0.0
     */
    protected $views = [];

    /**
     * View data.
     *
     * @var array
     * @since 1.0.0
     */
    protected $data = [];

    /**
     * View Localization.
     *
     * @var array
     * @since 1.0.0
     */
    protected $l11n = null;

    /**
     * Application.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    protected $app = null;

    /**
     * Request.
     *
     * @var RequestAbstract
     * @since 1.0.0
     */
    protected $request = null;

    /**
     * Request.
     *
     * @var ResponseAbstract
     * @since 1.0.0
     */
    protected $response = null;

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app      Application
     * @param RequestAbstract     $request  Request
     * @param ResponseAbstract    $response Request
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ApplicationAbstract $app, RequestAbstract $request, ResponseAbstract $response)
    {
        $this->app      = $app;
        $this->request  = $request;
        $this->response = $response;

        $this->l11n = new Localization();
    }

    /**
     * Sort views by order.
     *
     * @param array $a Array 1
     * @param array $b Array 2
     *
     * @return \int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private static function viewSort(array $a, array $b) : \int
    {
        if ($a['order'] === $b['order']) {
            return 0;
        }

        return ($a['order'] < $b['order']) ? -1 : 1;
    }

    /**
     * Get the template.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getTemplate() : \string
    {
        return $this->template;
    }

    /**
     * Set the template.
     *
     * @param \string $template
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setTemplate(\string $template)
    {
        $this->template = $template;
    }

    /**
     * @return View[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getViews() : array
    {
        return $this->views;
    }

    /**
     * @param \string $id View ID
     *
     * @return false|View
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getView($id)
    {
        if (!isset($this->views[$id])) {
            return false;
        }

        return $this->views[$id];
    }

    /**
     * Remove view.
     *
     * @param \string $id View ID
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeView(\string $id) : \bool
    {
        if (isset($this->views[$id])) {
            unset($this->views[$id]);

            return true;
        }

        return false;
    }

    /**
     * Edit view.
     *
     * @param \string   $id    View ID
     * @param View      $view
     * @param null|\int $order Order of view
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function editView(\string $id, View $view, $order = null)
    {
        $this->addView($id, $view, $order, true);
    }

    /**
     * Add view.
     *
     * @param \string   $id        View ID
     * @param View      $view
     * @param null|\int $order     Order of view
     * @param \bool     $overwrite Overwrite existing view
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addView(\string $id, View $view, $order = null, \bool $overwrite = true)
    {
        if ($overwrite || !isset($this->views[$id])) {
            $this->views[$id] = $view;

            if ($order !== null) {
                $this->views = uasort($this->views, ['\phpOMS\Views\View', 'viewSort']);
            }
        }
    }

    /**
     * Get view/template response of all views.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function renderAll() : \string
    {
        ob_start();

        foreach ($this->views as $key => $view) {
            echo $view->render();
        }

        return ob_get_clean();
    }

    /**
     * Get view/template response.
     *
     * @return \string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function render() : \string
    {
        $this->l11n->setLang($this->app->l11nManager->getLanguage($this->response->getL11n()->getLanguage()));
        $path = realpath(__DIR__ . '/../..' . $this->template . '.tpl.php');

        if (strpos($path, ROOT_PATH) === false) {
            throw new FilePathException(__DIR__ . '/../..' . $this->template . '.tpl.php');
        }

        ob_start();
        /** @noinspection PhpIncludeInspection */
        include $path;

        return ob_get_clean();
    }

    /**
     * @param \string $id Data Id
     *
     * @return mixed
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getData($id)
    {
        return $this->data[$id] ?? null;
    }

    /**
     * @param \string $id   Data ID
     * @param mixed   $data Data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setData(\string $id, $data)
    {
        $this->data[$id] = $data;
    }

    /**
     * Remove view.
     *
     * @param \string $id Data Id
     *
     * @return \bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function removeData(\string $id) : \bool
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);

            return true;
        }

        return false;
    }

    /**
     * @param \string $id   Data ID
     * @param mixed   $data Data
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addData(\string $id, $data)
    {
        $this->data[$id] = $data;
    }

}
