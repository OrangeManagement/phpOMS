<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Base
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Enum class.
 *
 * Replacing the SplEnum class and providing basic enum.
 *
 * @package    phpOMS\Stdlib\Base
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class EnumArray
{
    /**
     * Constants.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $constants = [];

    /**
     * Checking enum name.
     *
     * Checking if a certain const name exists (case sensitive)
     *
     * @param string $name Name of the value (case sensitive)
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isValidName(string $name) : bool
    {
        $constants = self::getConstants();

        return isset($constants[$name]);
    }

    /**
     * Getting all constants of this enum.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getConstants() : array
    {
        /** @var array $constants */
        return (new static())::$constants;
    }

    /**
     * Check enum value.
     *
     * Checking if a given value is part of this enum
     *
     * @param mixed $value Value to check
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public static function isValidValue($value) : bool
    {
        $constants = self::getConstants();

        return \in_array($value, $constants, true);
    }

    /**
     * Get enum value by name.
     *
     * @param mixed $key Key to look for
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function get($key)
    {
        $constants = self::getConstants();

        if (!isset($constants[$key])) {
            throw new \OutOfBoundsException('Key "' . $key . '" is not valid.');
        }

        return $constants[$key];
    }
}
