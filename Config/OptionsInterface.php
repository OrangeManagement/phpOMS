<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Config
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Config;

/**
 * Options class.
 *
 * @package    phpOMS\Config
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
interface OptionsInterface
{

    /**
     * Is this key set.
     *
     * @param mixed $key Key to check for existence
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function exists($key) : bool;

    /**
     * Updating or adding settings.
     *
     * @param mixed $key       Unique option key
     * @param mixed $value     Option value
     * @param bool  $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function setOption($key, $value, bool $overwrite = true) : bool;

    /**
     * Updating or adding settings.
     *
     * @param array $pair      Key value pair
     * @param bool  $overwrite Overwrite existing value
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function setOptions(array $pair, bool $overwrite = true) : bool;

    /**
     * Get option by key.
     *
     * @param mixed $key Unique option key
     *
     * @return mixed Option value
     *
     * @since  1.0.0
     */
    public function getOption($key);
}
