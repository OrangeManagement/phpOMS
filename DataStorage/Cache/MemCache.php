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
namespace phpOMS\DataStorage\Cache;

/**
 * Memcache class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class MemCache implements CacheInterface
{

    /**
     * Memcache instance.
     *
     * @var \Memcache
     * @since 1.0.0
     */
    private $memc = null;

    /**
     * Only cache if data is larger than threshold (0-100).
     *
     * @var \int
     * @since 1.0.0
     */
    private $threshold = 10;

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct()
    {
        $this->memc = new self();
    }

    /**
     * Adding server to server pool.
     *
     * @param mixed $data Server data array
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function addServer($data)
    {
        $this->memc->addServer($data['host'], $data['port'], $data['timeout']);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, CacheStatus $type = null, \int $expire = 2592000)
    {
        $this->memc->set($key, $value, false, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, CacheStatus $type = null, \int $expire = 2592000)
    {
        return $this->memc->add($key, $value, false, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, CacheStatus $type = null)
    {
        return $this->memc->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, CacheStatus $type = null)
    {
        $this->memc->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush(CacheStatus $type = null)
    {
        $this->memc->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, CacheType $type = null, \int $expire = -1)
    {
        $this->memc->replace($key, $value, false, $expire);
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        return $this->memc->getExtendedStats();
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : \int
    {
        return $this->threshold;
    }

    /**
     * Destructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Closing cache.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function close()
    {
        if ($this->memc !== null) {
            $this->memc->close();
            $this->memc = null;
        }
    }

}
