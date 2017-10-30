<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types = 1);

namespace phpOMS\Utils;

/**
 * Color class for color operations.
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ColorUtils
{

    /**
     * Creates a 3 point gradient based on a input value.
     *
     * @param int   $value Value to represent by color
     * @param int[] $start Gradient start
     * @param int[] $stop  Gradient stop
     * @param int[] $end   Gradient end
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function getRGBGradient(int $value, array $start, array $stop, array $end) : array
    {
        $diff     = [];
        $gradient = [];

        if ($value <= $stop[0] && $value < $start[0]) {
            $value = $start[0];
        } else {
            $value = min($value, $end[0]);
            $start = $stop;
            $stop  = $end;
        }

        $diff[0] = $stop[0] - $start[0];
        $diff[1] = $stop[1] - $start[1];
        $diff[2] = $stop[2] - $start[2];
        $diff[3] = $stop[3] - $start[3];

        $gradient['r'] = $start[1] + ($value - $start[0]) / ($diff[0]) * $diff[1];
        $gradient['g'] = $start[2] + ($value - $start[0]) / ($diff[0]) * $diff[2];
        $gradient['b'] = $start[3] + ($value - $start[0]) / ($diff[0]) * $diff[3];

        foreach ($gradient as &$color) {
            $color = max(min($color, 255), 0);
        }

        return $gradient;
    }

    /**
     * Convert int to rgb
     *
     * @param int   $rgbInt Value to convert
     *
     * @return array
     *
     * @since  1.0.0
     */
    public static function intToRgb(int $rgbInt) : array
    {
        $rgb = ['r' => 0, 'g' => 0, 'b' => 0];

        $rgb['b'] = $rgbInt & 255;
        $rgb['g'] = ($rgbInt >> 8) & 255;
        $rgb['r'] = ($rgbInt >> 16) & 255;

        return $rgb;
    }
}
