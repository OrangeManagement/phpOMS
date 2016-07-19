<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */

namespace phpOMS\Math\Statistic;

/**
 * Average class.
 *
 * @category   Framework
 * @package    phpOMS\DataStorage\Database
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Average
{

    const MA3 = [1/3, 1/3];
    const MA5 = [0.2, 0.2, 0.2];
    const MA2x12 = [5/6, 5/6, 5/6, 5/6, 5/6, 5/6, 0.42];
    const MA3x3 = [1/3, 2/9, 1/9];
    const MA3x5 = [0.2, 0.2, 2/15, 4/6];
    const MAS15 = [0.231, 0.209, 0.144, 2/3, 0.009, -0.016, -0.019, -0.009]
    const MAS21 = [0.171, 0.163, 0.134, 0.37, 0.51, 0.017, -0.006, -0.014, -0.014, -0.009, -0.003];
    const MAH5 = [0.558, 0.294, -0.73];
    const MAH9 = [0.330, 0.267, 0.119, -0.010, -0.041];
    const MAH13 = [0.240, 0.214, 0.147, 0.66, 0, -0.028, -0.019];
    const MAH23 = [0.148, 0.138, 0.122, 0.097, 0.068, 0.039, 0.013, -0.005, -0.015, -0.016, -0.011, -0.004];

    /**
     * Calculate weighted average.
     *
     * Example: ([1, 2, 3, 4], [0.25, 0.5, 0.125, 0.125])
     *
     * @param array $values Values
     * @param array $weight Weight for values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function weightedAverage(array $values, array $weight) : float
    {
        if (($count = count($values)) !== count($weight)) {
            throw new \Exception('Dimension');
        }

        $avg = 0.0;

        for ($i = 0; $i < $count; $i++) {
            $avg += $values[$i] * $weight[$i];
        }

        return $avg;
    }

    /**
     * Average change.
     *
     * @param array $x Dataset
     * @param int   $h Future steps
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function averageChange(array $x, int $h = 1) : float
    {
        $count = count($x);

        return $x[$count - 1] + $h * ($x[$count - 1] - $x[0]) / ($count - 1);
    }

    /**
     * Moving average or order m.
     *
     * @param array $x Dataset
     * @param int   $t Current period
     * @param int   $periods Periods to use for average
     *
     * @return float
     *
     * @todo: allow counter i also to go into the future... required for forecast how? should be doable!
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function movingAverage(array $x, int $t, int $order, array $weight = null, bool $symmetric = false) : float {
        $periods = (int) ($order / 2);
        if($t < $periods || ($count = count($x)) < $periods) || ($symmetric && $t + $periods < $count)) {
            throw new \Exception('Periods');
        }

        $end = $symmetric ? $periods - 1 : 0;
        $end = $order % 2 === 0 ? $end - 1 : $end;
        $start = $t - 1 -($periods - 2);

        if(isset($weight)) {
            return self::weightedAverage(array_slice($x, $start, $end-$start), array_slice($weight, $start, $end-$start));
        } else {
            return self::arithmeticMean(array_slice($x, $start, $end-$start));
        }
    }

    /**
     * t = 3 and p = 3 means -1 0 +1, t = 4 and p = 2 means -1 0
     * periods should be replaced with order than it's possible to test for even or odd m
     * todo: maybe floor()?
     */
    public static function totalMovingAverage(array $x, int $order, array $weight = null, bool $symmetric = false) : array {
        $periods = (int) ($order / 2);
        $count = count($x) - ($symmetric ? $periods : 0);
        $avg = [];

        for($i = $periods; $i < $count; $i++) {
            $avg[] = self::movingAverage($x, $i, $order, $weight, $symmetric);
        }

        return $avg;
    }

    /**
     * Calculate the mode.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function mode($values)
    {
        $count = array_count_values($values);
        $best  = max($count);

        return array_keys($count, $best);
    }

    /**
     * Calculate the median.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function median(array $values) : float
    {
        sort($values);
        $count     = count($values);
        $middleval = (int) floor(($count - 1) / 2);

        if ($count % 2) {
            $median = $values[$middleval];
        } else {
            $low    = $values[$middleval];
            $high   = $values[$middleval + 1];
            $median = ($low + $high) / 2;
        }

        return $median;
    }

    /**
     * Calculate the arithmetic mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $values Values
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function arithmeticMean(array $values)
    {
        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        return array_sum($values) / $count;
    }

    /**
     * Calculate the geometric mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $values Values
     * @param int   $offset Offset for outlier
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function geometricMean(array $values, int $offset = 0)
    {
        $count = count($values);

        if ($count === 0) {
            throw new \Exception('Division zero');
        }

        return pow(array_product($values), 1 / $count);
    }

    /**
     * Calculate the harmonic mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $values Values
     * @param int   $offset Offset for outlier
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function harmonicMean(array $values, int $offset = 0)
    {
            }

            $sum += 1 / $value;
        }

        return 1 / ($sum / $count);
    }

    /**
     * Calculate the angle mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $angles Angles
     * @param int   $offset Offset for outlier
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function angleMean($angles, int $offset = 0)
    {
        $y    = $x = 0;
        $size = count($angles);

        for ($i = 0; $i < $size; $i++) {
            $x += cos(deg2rad($angles[$i]));
            $y += sin(deg2rad($angles[$i]));
        }

        $x /= $size;
        $y /= $size;

        return rad2deg(atan2($y, $x));
    }

    /**
     * Calculate angle based on time.
     *
     * Example: ('08:44:28')
     *
     * @param string $time Time
     *
     * @return float
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function timeToAngle(string $time) : float
    {
        $parts = explode(':', $time);

        if (count($parts) !== 3) {
            throw new \Exception('Wrong time format');
        }

        $sec   = ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
        $angle = 360.0 * ($sec / 86400.0);

        return $angle;
    }

    /**
     * Calculate time based on angle.
     *
     * Example: ('08:44:28')
     *
     * @param float $angle Angle
     *
     * @return string
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function angleToTime(float $angle) : string
    {
        $sec  = 86400.0 * $angle / 360.0;
        $time = sprintf('%02d:%02d:%02d', floor($sec / 3600), floor(($sec % 3600) / 60), $sec % 60);

        return $time;
    }

    /**
     * Calculate the angle mean.
     *
     * Example: ([1, 2, 2, 3, 4, 4, 2])
     *
     * @param array $angles Angles
     * @param int   $offset Offset for outlier
     *
     * @return float
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function angleMean2(array $angles, int $offset = 0)
    {
        sort($angles);

        if ($offset > 0) {
            $angles = array_slice($angles, $offset, -$offset);
        }

        $sins = 0.0;
        $coss = 0.0;

        foreach ($angles as $a) {
            $sins += sin(deg2rad($a));
            $coss += cos(deg2rad($a));
        }

        $avgsin = $sins / (0.0 + count($angles));
        $avgcos = $coss / (0.0 + count($angles));
        $avgang = rad2deg(atan2($avgsin, $avgcos));

        while ($avgang < 0.0) {
            $avgang += 360.0;
        }

        return $avgang;
    }
}
