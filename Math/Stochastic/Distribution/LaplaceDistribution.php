<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Stochastic\Distribution;

/**
 * Laplace distribution.
 *
 * @package    phpOMS\Math\Stochastic\Distribution
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class LaplaceDistribution
{
    /**
     * Get probability density function.
     *
     * @param float $x  Value x
     * @param float $mu Value mu
     * @param float $b  Value b
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getPdf(float $x, float $mu, float $b) : float
    {
        return 1 / (2 * $b) * exp(-abs($x - $mu) / $b);
    }

    /**
     * Get cumulative distribution function.
     *
     * @param float $x  Value x
     * @param float $mu Value mu
     * @param float $b  Value b
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getCdf(float $x, float $mu, float $b) : float
    {
        return $x < $mu ? exp(($x - $mu) / $b) / 2 : 1 - exp(-($x - $mu) / $b) / 2;
    }

    /**
     * Get mode.
     *
     * @param float $mu Value mu
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getMode(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get expected value.
     *
     * @param float $mu Value mu
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getMean(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get expected value.
     *
     * @param float $mu Value mu
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getMedian(float $mu) : float
    {
        return $mu;
    }

    /**
     * Get variance.
     *
     * @param float $b Value b
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getVariance(float $b) : float
    {
        return 2 * $b ** 2;
    }

    /**
     * Get moment generating function.
     *
     * @param float $t  Valute t
     * @param float $mu Value mu
     * @param float $b  Value b
     *
     * @return float
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getMgf(float $t, float $mu, float $b) : float
    {
        if ($t >= 1 / $b) {
            throw new \Exception('Out of bounds');
        }

        return exp($mu * $t) / (1 - $b ** 2 * $t ** 2);
    }

    /**
     * Get skewness.
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getSkewness() : float
    {
        return 0;
    }

    /**
     * Get Ex. kurtosis.
     *
     * @return float
     *
     * @since  1.0.0
     */
    public static function getExKurtosis() : float
    {
        return 3;
    }

    public static function getRandom()
    {

    }
}
