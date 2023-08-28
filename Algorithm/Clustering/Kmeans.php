<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Clustering
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Clustering;

use phpOMS\Math\Topology\MetricsND;

/**
 * Clustering points
 *
 * @package phpOMS\Algorithm\Clustering
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 * @see     ./clustering_overview.png
 */
final class Kmeans
{
    /**
     * Epsilon for float comparison.
     *
     * @var float
     * @since 1.0.0
     */
    public const EPSILON = 4.88e-04;

    /**
     * Metric to calculate the distance between two points
     *
     * @var \Closure
     * @since 1.0.0
     */
    private \Closure $metric;

    /**
     * Points of the cluster centers
     *
     * @var PointInterface[]
     * @since 1.0.0
     */
    private $clusterCenters = [];

    /**
     * Constructor
     *
     * @param null|\Closure    $metric   metric to use for the distance between two points
     *
     * @since 1.0.0
     */
    public function __construct(\Closure $metric = null)
    {
        $this->metric = $metric ?? function (PointInterface $a, PointInterface $b) {
            $aCoordinates = $a->coordinates;
            $bCoordinates = $b->coordinates;

            return MetricsND::euclidean($aCoordinates, $bCoordinates);
        };

        //$this->generateClusters($points, $clusters);
    }

    /**
     * Find the cluster for a point
     *
     * @param PointInterface $point Point to find the cluster for
     *
     * @return null|PointInterface Cluster center point
     *
     * @since 1.0.0
     */
    public function cluster(PointInterface $point) : ?PointInterface
    {
        $bestCluster  = null;
        $bestDistance = \PHP_FLOAT_MAX;

        foreach ($this->clusterCenters as $center) {
            if (($distance = ($this->metric)($center, $point)) < $bestDistance) {
                $bestCluster  = $center;
                $bestDistance = $distance;
            }
        }

        return $bestCluster;
    }

    /**
     * Get cluster centroids
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function getCentroids() : array
    {
        return $this->clusterCenters;
    }

    /**
     * Generate the clusters of the points
     *
     * @param PointInterface[] $points   Points to cluster
     * @param int<0, max>      $clusters Amount of clusters
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function generateClusters(array $points, int $clusters) : void
    {
        $n              = \count($points);
        $clusterCenters = $this->kpp($points, $clusters);
        $coordinates    = \count($points[0]->coordinates);

        while (true) {
            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    $center->setCoordinate($i, 0);
                }
            }

            foreach ($points as $point) {
                $clusterPoint = $clusterCenters[$point->group];

                ++$clusterPoint->group;
                for ($i = 0; $i < $coordinates; ++$i) {
                    $clusterPoint->setCoordinate($i, $clusterPoint->getCoordinate($i) + $point->getCoordinate($i));
                }
            }

            foreach ($clusterCenters as $center) {
                for ($i = 0; $i < $coordinates; ++$i) {
                    // @todo Invalid center coodinate value in like 5 % of the runs
                    $center->setCoordinate($i, $center->getCoordinate($i) / ($center->group === 0 ? 1 : $center->group));
                }
            }

            $changed = 0;
            foreach ($points as $point) {
                $min = $this->nearestClusterCenter($point, $clusterCenters)[0];

                if ($min !== $point->group) {
                    ++$changed;
                    $point->group = $min;
                }
            }

            if ($changed <= $n * self::EPSILON || $n * self::EPSILON < 2) {
                break;
            }
        }

        foreach ($clusterCenters as $key => $center) {
            $center->group = $key;
            $center->name  = (string) $key;
        }

        $this->clusterCenters = $clusterCenters;
    }

    /**
     * Get the index and distance to the nearest cluster center
     *
     * @param PointInterface   $point          Point to get the cluster for
     * @param PointInterface[] $clusterCenters All cluster centers
     *
     * @return array [index, distance]
     *
     * @since 1.0.0
     */
    private function nearestClusterCenter(PointInterface $point, array $clusterCenters) : array
    {
        $index = $point->group;
        $dist  = \PHP_FLOAT_MAX;

        foreach ($clusterCenters as $key => $cPoint) {
            $d = ($this->metric)($cPoint, $point);

            if ($dist > $d) {
                $dist  = $d;
                $index = $key;
            }
        }

        return [$index, $dist];
    }

    /**
     * Initializae cluster centers
     *
     * @param PointInterface[] $points Points to use for the cluster center initialization
     * @param int<0, max>      $n      Amount of clusters to use
     *
     * @return PointInterface[]
     *
     * @since 1.0.0
     */
    private function kpp(array $points, int $n) : array
    {
        $clusters = [clone $points[\mt_rand(0, \count($points) - 1)]];
        $d        = \array_fill(0, $n, 0.0);

        for ($i = 1; $i < $n; ++$i) {
            $sum = 0;

            foreach ($points as $key => $point) {
                $d[$key] = $this->nearestClusterCenter($point, \array_slice($clusters, 0, 5))[1];
                $sum    += $d[$key];
            }

            $sum *= \mt_rand(0, \mt_getrandmax()) / \mt_getrandmax();

            foreach ($d as $key => $di) {
                $sum -= $di;

                if ($sum <= 0) {
                    $clusters[$i] = clone $points[$key];
                }
            }
        }

        foreach ($points as $point) {
            $point->group = ($this->nearestClusterCenter($point, $clusters)[0]);
        }

        return $clusters;
    }
}
