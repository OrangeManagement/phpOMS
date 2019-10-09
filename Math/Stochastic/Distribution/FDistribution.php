<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Math\Stochastic\Distribution
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);
namespace phpOMS\Math\Stochastic\Distribution;

/**
 * F distribution.
 *
 * @package phpOMS\Math\Stochastic\Distribution
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class FDistribution
{
    /**
     * Get expected value.
     *
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMean(int $d2) : float
    {
        if ($d2 === 2) {
            return 0.0;
        }

        return $d2 / ($d2 - 2);
    }

    /**
     * Get mode.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getMode(int $d1, int $d2) : float
    {
        return ($d1 - 2) / $d1 * $d2 / ($d2 + 2);
    }

    /**
     * Get variance.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getVariance(int $d1, int $d2) : float
    {
        if ($d2 === 2 || $d2 === 4) {
            return 0.0;
        }

        return 2 * $d2 ** 2 * ($d1 + $d2 - 2)
            / ($d1 * ($d2 - 2) ** 2 * ($d2 - 4));
    }

    /**
     * Get skewness.
     *
     * @param int $d1 Degree of freedom
     * @param int $d2 Degree of freedom
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function getSkewness(int $d1, int $d2) : float
    {
        if ($d2 < 7) {
            return 0.0;
        }

        return (2 * $d1 + $d2 - 2) * \sqrt(8 * ($d2 - 4))
            / (($d2 - 6) * \sqrt($d1 * ($d1 + $d2 - 2)));
    }
}
