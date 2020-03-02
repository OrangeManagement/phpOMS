<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Config
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Config;

/**
 * Options class.
 *
 * @package phpOMS\Config
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface SettingsInterface extends OptionsInterface
{
    /**
     * Get option.
     *
     * Possible usage:
     *      - Use column key
     *      - Use combination of module, group, account and name without column key
     *
     * @param null|int|int[]|string|string[] $ids     Ids
     * @param null|string|string[]           $names   Setting name
     * @param null|string                    $module  Module name
     * @param null|int                       $group   Group id
     * @param null|int                       $account Account id
     *
     * @return mixed Option value
     *
     * @since 1.0.0
     */
    public function get(
        $ids = null,
        $names = null,
        string $module = null,
        int $group = null,
        int $account = null
    );

    /**
     * Set option by key.
     *
     * @param array<int, array{id?:?int, name?:?string, content:string, module?:?string, group?:?int, account?:?int}> $options Column values for filtering
     * @param bool                                                                                                    $store   Save this Setting immediately to database
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function set(array $options, bool $store = false) : void;

    /**
     * Save options.
     *
     * @param array<int, array{id?:?int, name?:?string, content:string, module?:?string, group?:?int, account?:?int}> $options Options to save
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function save(array $options = []) : void;

    /**
     * Create option.
     *
     * @param array $options Options to save
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function create(array $options = []) : void;
}