<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\DataStorage\Cache;

use phpOMS\Config\OptionsInterface;
use phpOMS\Config\OptionsTrait;

/**
 * Cache class.
 *
 * Responsible for caching scalar data types and arrays.
 * Caching HTML output and objects coming soon/is planned.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class CachePool implements OptionsInterface
{
    use OptionsTrait;

    /**
     * MemCache instance.
     *
     * @var \phpOMS\DataStorage\Cache\CacheInterface[]
     * @since 1.0.0
     */
    private $pool = null;

    /**
     * Constructor.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Add database.
     *
     * @param mixed          $key   Database key
     * @param CacheInterface $cache Cache
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function add(string $key = 'core', CacheInterface $cache) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = $cache;

        return true;
    }

    /**
     * Remove database.
     *
     * @param mixed $key Database key
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function remove(string $key) : bool
    {
        if (!isset($this->pool[$key])) {
            return false;
        }

        unset($this->pool[$key]);

        return true;
    }

    /**
     * Requesting caching instance.
     *
     * @param string $key Cache to request
     *
     * @return \phpOMS\DataStorage\Cache\CacheInterface
     *
     * @since  1.0.0
     */
    public function get(string $key = '') /* : ?CacheInterface */
    {
        if ((!empty($key) && !isset($this->pool[$key])) || empty($this->pool)) {
            return null;
        }

        if (empty($key)) {
            return reset($this->pool);
        }

        return $this->pool[$key];
    }

    /**
     * Create Cache.
     *
     * @param mixed $key    Database key
     * @param array $config Database config data
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function create(string $key, array $config) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = CacheFactory::create($config);

        return true;
    }
}
