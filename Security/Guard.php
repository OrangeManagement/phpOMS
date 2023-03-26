<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Security;

use phpOMS\System\File\FileUtils;

/**
 * Php code security class.
 *
 * This can be used to guard against certain vulnerabilities
 *
 * @package phpOMS\Security
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Guard
{
    /**
     * Base path for the application
     *
     * @var string
     * @since 1.0.0
     */
    public static string $BASE_PATH = __DIR__ . '/../../';

    /**
     * Make sure a path is part of a base path
     *
     * This can be used to verify if a path goes outside of the allowed path bounds
     *
     * @param string $path Path to check
     * @param string $base Base path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafePath(string $path, string $base = '') : bool
    {
        return \stripos(FileUtils::absolute($path), FileUtils::absolute(empty($base) ? self::$BASE_PATH : $base)) === 0;
    }
}