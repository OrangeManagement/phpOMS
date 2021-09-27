<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Uninstaller abstract class.
 *
 * @package phpOMS\Application
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class UninstallerAbstract
{
    /**
     * Install module.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function uninstall(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        self::deactivate($dbPool, $info);
        self::dropTables($dbPool, $info);
        self::unregisterFromDatabase($dbPool, $info);
    }

    /**
     * Activate after install.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function deactivate(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        /** @var StatusAbstract $class */
        $class = '\Web\\' . $info->getInternalName() . '\Admin\Status';
        $class::deactivate($dbPool, $info);
    }

    /**
     * Drop tables of app.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function dropTables(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $path = __DIR__ . '/../../Web/' . $info->getInternalName() . '/Admin/Install/db.json';

        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        $definitions = \json_decode($content, true);
        $builder     = new SchemaBuilder($dbPool->get('schema'));

        foreach ($definitions as $definition) {
            $builder->dropTable($definition['table'] ?? '');
        }

        $builder->execute();
    }

    /**
     * Unregister app from database.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function unregisterFromDatabase(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $queryApp = new Builder($dbPool->get('delete'));
        $queryApp->delete()
            ->from('app')
            ->where('app_name', '=', $info->getInternalName())
            ->execute();
    }
}
