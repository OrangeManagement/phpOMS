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
 namespace phpOMS\Math\Finance\Forecasting\ExponentialSmoothing;

use phpOMS\Math\Finance\Forecasting\SmoothingType;
use phpOMS\Math\Statistic\Average;
use phpOMS\Math\Statistic\Forecast\Error;

class Brown implements ExponentialSmoothingInterface
{
    private $data = [];

    private $errors = [];

    private $cycle = 0;

    private $type = ForecastType::LINEAR;

    private $rmse = 0.0;

    private $mse = 0.0;

    private $mae = 0.0;

    private $sse = 0.0;

    public function __construct(array $data, int $cycle = 0, int $type = ForecastType::LINEAR)
    {
        $this->data  = $data;
        $this->type = $type;
    }

    public function setCycle(int $cycle) /* : void */
    {
        $this->cycle = $cycle;
    }

    public function getRMSE() : float
    {
        return $this->rmse;
    }

    public function getMSE() : float
    {
        return $this->mse;
    }

    public function getMAE() : float
    {
        return $this->mae;
    }

    public function getSSE() : float
    {
        return $this->sse;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    public function getForecast(int $future = 1, int $smoothing = SmoothingType::CENTERED_MOVING_AVERAGE) : array
    {
        $trendCycle                  = $this->getTrendCycle($this->cycle, $smoothing);
        $seasonality                 = $this->getSeasonality($trendCycle);
        $seasonalityIndexMap         = $this->generateSeasonalityMap($this->cycle, $seasonality);
        $adjustedSeasonalityIndexMap = $this->generateAdjustedSeasonalityMap($this->cycle, $seasonalityIndexMap);
        $adjustedData                = $this->getAdjustedData($this->cycle, $adjustedSeasonalityIndexMap);
        $optimizedForecast           = $this->getOptimizedForecast($future, $adjustedData);

        return $this->getReseasonalized($this->cycle, $optimizedForecast, $adjustedSeasonalityIndexMap);
    }

    private function getTrendCycle(int $cycle, int $smoothing) : array
    {
        $centeredMovingAverage = [];

        $length = count($this->data);
        for ($i = $cycle; $i < $length - $cycle; $i++) {
            $centeredMovingAverage[$i] = Average::arithmeticMean(array_slice($this->data, $i - $cycle, $cycle));
        }

        return $centeredMovingAverage;
    }

    private function getSeasonality(array $trendCycle) : array
    {
        $seasonality = [];
        foreach ($trendCycle as $key => $value) {
            $seasonality[$key] = $this->data[$key] / $value;
        }

        return $seasonality;
    }

    private function generateSeasonalityMap(int $cycle, array $seasonality) : array
    {
        $map = [];
        foreach ($seasonality as $key => $value) {
            $map[$key % $cycle][] = $value;
        }

        foreach ($map as $key => $value) {
            $map[$key] = Average::arithmeticMean($value);
        }

        return $map;
    }

    private function generateAdjustedSeasonalityMap(int $cycle, array $seasonality) : array
    {
        $total = array_sum($seasonality);

        foreach ($seasonality as $key => $value) {
            $seasonality[$key] = $cycle * $value / $total;
        }

        return $seasonality;
    }

    private function getSeasonalIndex(int $cycle, array $seasonalityMap) : array
    {
        $index = [];

        foreach ($this->data as $key => $value) {
            $index[$key] = $seasonalityMap[$key % $cycle];
        }

        return $index;
    }

    private function getAdjustedData(int $cycle, array $seasonalIndex) : array
    {
        $adjusted = [];

        foreach ($this->data as $key => $value) {
            $adjusted[$key] = $value / ($seasonalIndex[$key % $cycle] === 0 ? 1 : $seasonalIndex[$key % $cycle]);
        }

        return $adjusted;
    }

    private function forecastLinear(int $future, float $alpha, array $data, array &$error) : array
    {
        $forecast = [];
        $dataLength = count($data);
        $length   = $dataLength + $future;

        $forecast[0] = $data[0];
        $forecast[1] = $data[1];

        $error[0] = 0;
        $error[1] = $data[1] - $forecast[1];

        for ($i = 2; $i < $length; $i++) {
            $forecast[$i] = 2 * ($data[$i - 1] ?? $forecast[$i - 1]) - ($data[$i - 2] ?? $forecast[$i - 2]) - 2 * (1 - $alpha) * $error[$i - 1] + pow(1 - $alpha, 2) * $error[$i - 2];
            $error[$i]    = $i < $dataLength ? $data[$i] - $forecast[$i] : 0;
        }

        return $forecast;
    }

    private function forecastSimple(int $future, float $alpha, array $data, array &$error) : array
    {
        $forecast = [];
        $dataLength = count($data);
        $length   = $dataLength + $future;

        $forecast[0] = $data[0];

        $error[0] = 0;

        for ($i = 1; $i < $length; $i++) {
            $forecast[$i] = $alpha * $data[$i-1] + (1-$alpha)*$forecast[$i-1];
            $error[$i]    = $i < $dataLength ? $data[$i] - $forecast[$i] : 0;
        }

        return $forecast;
    }

    private function getOptimizedForecast(int $future, array $adjustedDatae) : array
    {
        $this->rmse = PHP_INT_MAX;
        $alpha      = 0.00;
        $forecast   = [];

        while ($alpha < 1) {
            $error      = [];

            if($this->type === ForecastType::LINEAR) {
                $tempForecast = $this->forecastLinear($future, $alpha, $adjustedData, $error);
            } else {
                $tempForecast = $this->forecastSimple($future, $alpha, $adjustedData, $error);
            }

            $alpha += 0.001;

            $tempRMSE = Error::getRootMeanSquaredError($error);

            if ($tempRMSE < $this->rmse) {
                $this->rmse = $tempRMSE;
                $forecast   = $tempForecast;
            }
        }

        $this->errors = $error;
        $this->mse    = Error::getMeanSquaredError($error);
        $this->mae    = Error::getMeanAbsoulteError($error);
        $this->sse    = Error::getSumSquaredError($error);

        return $forecast;
    }

    private function getReseasonalized(int $cycle, array $forecast, array $seasonalIndex) : array
    {
        $reSeasonalized = [];

        foreach ($forecast as $key => $value) {
            $reSeasonalized[$key] = $value * ($seasonalIndex[$key % $cycle] === 0 ? 1 : $seasonalIndex[$key % $cycle]);
        }

        return $reSeasonalized;
    }
}
