<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Utils\Encoding
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\Encoding;

/**
 * Encoding Interface
 *
 * @package    phpOMS\Utils\Encoding
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
interface EncodingInterface
{
    /**
     * Encode source text
     *
     * @param string $source Source text to decode
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function encode($source);

    /**
     * Dedecodes text
     *
     * @param string $decoded decoded text to dedecode
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function decode($decoded);
}
