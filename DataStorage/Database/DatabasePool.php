<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database;

use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Connection\NullConnection;
use phpOMS\DataStorage\DataStorageConnectionInterface;
use phpOMS\DataStorage\DataStoragePoolInterface;

/**
 * Database pool handler.
 *
 * @package    phpOMS\DataStorage\Database
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
final class DatabasePool implements DataStoragePoolInterface
{

    /**
     * Databases.
     *
     * @var DataStorageConnectionInterface[]
     * @since 1.0.0
     */
    private $pool = [];

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
     * @param string                         $key Database key
     * @param DataStorageConnectionInterface $db  Database
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function add(string $key, DataStorageConnectionInterface $db) : bool
    {
        if (isset($this->pool[$key])) {
            return false;
        }

        $this->pool[$key] = $db;

        return true;
    }

    /**
     * Get database.
     *
     * @param string $key Database key
     *
     * @return DataStorageConnectionInterface
     *
     * @since  1.0.0
     */
    public function get(string $key = '') : DataStorageConnectionInterface
    {
        if ((!empty($key) && !isset($this->pool[$key])) || empty($this->pool)) {
            return new NullConnection();
        }

        if (empty($key)) {
            return \reset($this->pool);
        }

        return $this->pool[$key];
    }

    /**
     * Remove database.
     *
     * @param string $key Database key
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
     * Create database.
     *
     * @param string $key    Database key
     * @param array  $config Database config data
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

        $this->pool[$key] = ConnectionFactory::create($config);

        return true;
    }
}
