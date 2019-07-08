<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS;

/**
 * Application class.
 *
 * This class contains all necessary application members. Access to them
 * is restricted to write once in order to prevent manipulation
 * and afterwards read only.
 *
 * @property string $appName
 * @property int $orgId
 * @property \phpOMS\DataStorage\Database\DatabasePool $dbPool
 * @property \phpOMS\Localization\L11nManager $l11nManager
 * @property \phpOMS\Router\Router $router
 * @property \phpOMS\DataStorage\Session\SessionInterface $sessionManager
 * @property \phpOMS\DataStorage\Cookie\CookieJar $cookieJar
 * @property \phpOMS\Module\ModuleManager $moduleManager
 * @property \phpOMS\Dispatcher\Dispatcher $dispatcher
 * @property \phpOMS\DataStorage\Cache\CachePool $cachePool
 * @property \Model\CoreSettings $appSettings
 * @property \phpOMS\Event\EventManager $eventManager
 * @property \phpOMS\Account\AccountManager $accountManager
 * @property \phpOMS\Log\FileLogger $logger
 *
 * @package    phpOMS
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class ApplicationAbstract
{

    /**
     * App name.
     *
     * @var string
     * @since 1.0.0
     */
    protected $appName = '';

    /**
     * Organization id.
     *
     * @var int
     * @since 1.0.0
     */
    protected $orgId = 0;

    /**
     * App theme.
     *
     * @var string
     * @since 1.0.0
     */
    protected $theme = '';

    /**
     * Database object.
     *
     * @var \phpOMS\DataStorage\Database\DatabasePool
     * @since 1.0.0
     */
    protected $dbPool = null;

    /**
     * Application settings object.
     *
     * @var \Model\CoreSettings
     * @since 1.0.0
     */
    protected $appSettings = null;

    /**
     * Account manager instance.
     *
     * @var \phpOMS\Account\AccountManager
     * @since 1.0.0
     */
    protected $accountManager = null;

    /**
     * Cache instance.
     *
     * @var \phpOMS\DataStorage\Cache\CachePool
     * @since 1.0.0
     */
    protected $cachePool = null;

    /**
     * ModuleManager instance.
     *
     * @var \phpOMS\Module\ModuleManager
     * @since 1.0.0
     */
    protected $moduleManager = null;

    /**
     * Router instance.
     *
     * @var \phpOMS\Router\Router
     * @since 1.0.0
     */
    protected $router = null;

    /**
     * Dispatcher instance.
     *
     * @var \phpOMS\Dispatcher\Dispatcher
     * @since 1.0.0
     */
    protected $dispatcher = null;

    /**
     * Session instance.
     *
     * @var \phpOMS\DataStorage\Session\SessionInterface
     * @since 1.0.0
     */
    protected $sessionManager = null;

    /**
     * Cookie instance.
     *
     * @var \phpOMS\DataStorage\Cookie\CookieJar
     * @since 1.0.0
     */
    protected $cookieJar = null;

    /**
     * Server localization.
     *
     * @var \phpOMS\Localization\Localization
     * @since 1.0.0
     */
    protected $l11nServer = null;

    /**
     * Server localization.
     *
     * @var \phpOMS\Log\FileLogger
     * @since 1.0.0
     */
    protected $logger = null;

    /**
     * L11n manager.
     *
     * @var \phpOMS\Localization\L11nManager
     * @since 1.0.0
     */
    protected $l11nManager = null;

    /**
     * Event manager.
     *
     * @var \phpOMS\Event\EventManager
     * @since 1.0.0
     */
    protected $eventManager = null;

    /**
     * Set values
     *
     * @param string $name  Variable name
     * @param string $value Variable value
     *
     * @return void
     *
     * @todo replace with proper setter (faster)
     *
     * @since  1.0.0
     */
    public function __set($name, $value) : void
    {
        if (!empty($this->{$name})) {
            return;
        }

        $this->{$name} = $value;
    }

    /**
     * Get values
     *
     * @param string $name Variable name
     *
     * @return mixed Returns the value of the application member
     *
     * @todo replace with proper getter (faster)
     *
     * @since  1.0.0
     */
    public function __get($name)
    {
        return $this->{$name};
    }
}
