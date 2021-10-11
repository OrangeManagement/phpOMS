<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Math\Geometry\Shape\D2
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Math\Geometry\Shape\D2;

/**
 * Triangle shape.
 *
 * @package phpOMS\Math\Geometry\Shape\D2
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class Triangle implements D2ShapeInterface
{
    /**
     * Area
     *
     *     .
     *    /|\
     *  a  h c
     * /   |  \
     * ----b---
     *
     * @param float $b Edge
     * @param float $h Height
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSurface(float $b, float $h) : float
    {
        return $h * $b / 2;
    }

    /**
     * Perimeter
     *
     * @param float $a Edge
     * @param float $b Edge
     * @param float $c Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getPerimeter(float $a, float $b, float $c) : float
    {
        return $a + $b + $c;
    }

    /**
     * Diagonal
     *
     * @param float $area Area
     * @param float $b    Edge
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getHeight(float $area, float $b) : float
    {
        return 2 * $area / $b;
    }

    /**
     * Calculate the hypothenuse
     *
     * @param mixed ...$vec Vector of values
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getHypot(...$vec) : float
    {
        $hypot = 0.0;
        foreach ($vec as $val) {
            $hypot += $val * $val;
        }

        return \sqrt($hypot);
    }
}
