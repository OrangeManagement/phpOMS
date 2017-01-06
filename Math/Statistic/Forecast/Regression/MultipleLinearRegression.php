<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
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
 namespace phpOMS\Math\Statistic\Forecast\Regression;

use phpOMS\Math\Matrix\Matrix;

class MultipleLinearRegression
{
    /**
     * {@inheritdoc}
     */
    public static function getRegression(array $x, array $y) : array
    {
        $X = new Matrix(count($x), count($x[0]));
        $X->setMatrix($x);
        $XT = $X->transpose();

        $Y = new Matrix(count($y));
        $Y->setMatrix($y);


        return $XT->mult($X)->inverse()->mult($XT)->mult($Y)->getMatrix();
    }

    public static function getVariance() : float
    {
    }

    public static function getPredictionInterval() : array
    {
    }
}