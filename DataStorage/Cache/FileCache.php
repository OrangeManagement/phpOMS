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

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;

/**
 * MemCache class.
 *
 * PHP Version 5.6
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Cache
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FileCache implements CacheInterface
{

    /**
     * Delimiter for cache meta data
     *
     * @var string
     * @since 1.0.0
     */
    const DELIM = '$';

    /**
     * Cache path.
     *
     * @var string
     * @since 1.0.0
     */
    private $cachePath = __DIR__ . '/../../../Cache';

    /**
     * Only cache if data is larger than threshold (0-100).
     *
     * @var int
     * @since 1.0.0
     */
    private $threshold = 50;

    /**
     * Cache status.
     *
     * @var int
     * @since 1.0.0
     */
    private $status = CacheStatus::ACTIVE;

    /**
     * Constructor
     *
     * @param array $config Cache config
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(array $config)
    {
        $path = $config['path'] ?? '';

        if (!file_exists($path)) {
            mkdir($path);
        }

        $this->cachePath = realpath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll() : bool
    {
        if ($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        array_map('unlink', glob($this->cachePath . '/*'));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(int $status) {
        if(!CacheStatus::isValidValue($status)) {
            throw new InvalidEnumValue($status);
        }

        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function stats() : array
    {
        $stats            = [];
        $stats['status']  = $this->status;
        $stats['count']   = Directory::count($this->cachePath);
        $stats['size']    = Directory::size($this->cachePath);
        $stats['changed'] = Directory::changed($this->cachePath);

        return $stats;
    }

    /**
     * {@inheritdoc}
     */
    public function getThreshold() : int
    {
        return $this->threshold;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, int $expire = -1)
    {
        if($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        $path = File::sanitize($key, '~');

        file_put_contents($this->cachePath . '/' . $path, $this->build($value, $expire));

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function add($key, $value, int $expire = -1) : bool
    {
        if($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        $path = File::sanitize($key, '~');

        if (!file_exists($this->cachePath . '/' . $path)) {
            file_put_contents($this->cachePath . '/' . $path, $this->build($value, $expire));

            return true;
        }

        return false;
    }

    /**
     * Removing all cache elements larger or equal to the expiration date. Call flushAll for removing persistent cache elements (expiration is negative) as well.
     *
     * @param mixed $value  Data to cache
     * @param int   $expire Expire date of the cached data
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function build($value, int $expire) : string
    {
        $type = $this->dataType($value);
        $raw  = $this->stringify($value, $type);

        return $type . self::DELIM . $expire . self::DELIM . $raw;
    }

    /**
     * Analyze caching data type.
     *
     * @param mixed $value Data to cache
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function dataType($value) : int
    {
        if (is_int($value)) {
            return CacheType::_INT;
        } elseif (is_float($value)) {
            return CacheType::_FLOAT;
        } elseif (is_string($value)) {
            return CacheType::_STRING;
        } elseif (is_bool($value)) {
            return CacheType::_BOOL;
        } elseif (is_array($value)) {
            return CacheType::_ARRAY;
        } elseif ($value instanceof \Serializable) {
            return CacheType::_SERIALIZABLE;
        } elseif ($value instanceof \JsonSerializable) {
            return CacheType::_JSONSERIALIZABLE;
        }

        throw new \InvalidArgumentException('Invalid value');
    }

    /**
     * Create string representation of data for storage
     *
     * @param mixed $value Value of the data
     * @param int   $type  Type of the cache data
     *
     * @return string
     *
     * @throws InvalidEnumValue
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function stringify($value, int $type) : string
    {
        if ($type === CacheType::_INT || $type === CacheType::_FLOAT || $type === CacheType::_STRING || $type === CacheType::_BOOL) {
            return (string) $value;
        } elseif ($type === CacheType::_ARRAY) {
            return json_encode($value);
        } elseif ($type === CacheType::_SERIALIZABLE) {
            return get_class($value) . self::DELIM . $value->serialize();
        } elseif ($type === CacheType::_JSONSERIALIZABLE) {
            return get_class($value) . self::DELIM . $value->jsonSerialize();
        }

        throw new InvalidEnumValue($type);
    }

    /**
     * Get expire offset
     *
     * @param string $raw Raw data
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function getExpire(string $raw) : int
    {
        $expireStart = strpos($raw, self::DELIM);
        $expireEnd   = strpos($raw, self::DELIM, $expireStart+1);

        return (int) substr($raw, $expireStart+1, $expireEnd - ($expireStart+1));
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, int $expire = -1)
    {
        if($this->status !== CacheStatus::ACTIVE) {
            return null;
        }

        $name = File::sanitize($key, '~');
        $path = $this->cachePath . '/' . $name;

        if(!file_exists($path)) {
            return null;
        }
        
        $created = Directory::created($path)->getTimestamp();
        $now     = time();

        if ($expire >= 0 && $created + $expire > $now) {
            return null;
        }

        $raw  = file_get_contents($path);
        $type = $raw[0];

        $expireStart = strpos($raw, self::DELIM);
        $expireEnd   = strpos($raw, self::DELIM, $expireStart+1);
        $cacheExpire = substr($raw, $expireStart+1, $expireEnd - ($expireStart+1));

        if ($cacheExpire >= 0 && $created + $cacheExpire < $now) {
            $this->delete($key);

            return null;
        }

        $value = null;

        switch ($type) {
            case CacheType::_INT:
                $value = (int) substr($raw, $expireEnd + 1);
                break;
            case CacheType::_FLOAT:
                $value = (float) substr($raw, $expireEnd + 1);
                break;
            case CacheType::_BOOL:
                $value = (bool) substr($raw, $expireEnd + 1);
                break;
            case CacheType::_STRING:
                $value = substr($raw, $expireEnd + 1);
                break;
            case CacheType::_ARRAY:
                $value = json_decode(substr($raw, $expireEnd + 1));
                break;
            case CacheType::_SERIALIZABLE:
            case CacheType::_JSONSERIALIZABLE:
                $namespaceStart = strpos($raw, self::DELIM, $expireEnd);
                $namespaceEnd   = strpos($raw, self::DELIM, $namespaceStart+1);
                $namespace      = substr($raw, $namespaceStart, $namespaceEnd);

                $value = $namespace::unserialize(substr($raw, $namespaceEnd + 1));
                break;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key, int $expire = -1) : bool
    {
        if($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        $name = File::sanitize($key, '~');
        $path = $this->cachePath . '/' . $name;

        if ($expire < 0 && file_exists($path)) {
            unlink($path);

            return true;
        }

        if ($expire >= 0) {
            $created     = Directory::created($name)->getTimestamp();
            $now         = time();
            $raw         = file_get_contents($path);
            $expireStart = strpos($raw, self::DELIM);
            $expireEnd   = strpos($raw, self::DELIM, $expireStart+1);
            $cacheExpire = substr($raw, $expireStart+1, $expireEnd - ($expireStart+1));

            if ($cacheExpire >= 0 && $created + $cacheExpire > $now) {
                unlink($path);

                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function flush(int $expire = 0) : bool
    {
        if ($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        $dir = new Directory($this->cachePath);
        $now = time();

        foreach ($dir as $file) {
            if ($file instanceof File) {
                $created = $file->getCreatedAt()->getTimestamp();
                if (
                    ($expire >= 0 && $created + $expire < $now)
                    || ($expire < 0 && $created + $this->getExpire($file->getContent()) < $now)
                ) {
                    unlink($file->getPath());
                }
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function replace($key, $value, int $expire = -1) : bool
    {
        if($this->status !== CacheStatus::ACTIVE) {
            return false;
        }

        $path = File::sanitize($key, '~');

        if (file_exists($this->cachePath . '/' . $path)) {
            file_put_contents($this->cachePath . '/' . $path, $this->build($value, $expire));

            return true;
        }

        return false;
    }
}
