<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\IO
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\IO;

/**
 * IO database mapper.
 *
 * @package phpOMS\Utils\IO
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
interface IODatabaseMapper
{
    /**
     * Insert data from excel sheet into database
     *
     * @param string        $path      File path
     * @param string        $table     Table name (empty = sheet name)
     * @param null|\Closure $transform Transform data before import
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function import(string $path, string $table = '', ?\Closure $transform = null) : void;

    /**
     * Select data from database and store in excel sheet
     *
     * @param string                                       $path    Output path
     * @param \phpOMS\DataStorage\Database\Query\Builder[] $queries Queries to execute
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function export(string $path, array $queries) : void;

    /**
     * Update data from excel sheet into database
     *
     * @param string $path  File path
     * @param string $table Table name (empty = sheet name)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function update(string $path, string $table = '') : void;

    /**
     * Create database schema
     *
     * @param string $path  File path
     * @param string $table Table name (empty = sheet name)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function createSchema(string $path, string $table = '') : void;
}
