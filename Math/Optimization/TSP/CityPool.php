<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Math\Optimization\TSP;

/**
 * City pool.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class CityPool implements \Countable
{
    /**
     * Cities
     *
     * @var array
     * @since 1.0.0
     */
    private $cities = [];

    /**
     * Constructor.
     *
     * @param City[] $cities Cities
     *
     * @since  1.0.0
     */
    public function __construct(array $cities = [])
    {
        $this->cities = $cities;
    }

    /**
     * Add city.
     *
     * @param City $city City
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addCity(City $city) : void
    {
        $this->cities[$city->getName() . $city->getLatitude() . $city->getLongitude()] = $city;
    }

    /**
     * Get city.
     *
     * @param int $index City index
     *
     * @return City
     *
     * @since  1.0.0
     */
    public function getCity(int $index) : City
    {
        return array_values($this->cities)[$index];
    }

    /**
     * Get cities.
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getCities() : array
    {
        return $this->cities;
    }

    /**
     * Has city.
     *
     * @param City $city City
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function hasCity(City $city) : bool
    {
        foreach ($this->cities as $c) {
            if ($c->equals($city)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Count cities
     *
     * @return int
     *
     * @since  1.0.0
     */
    public function count() : int
    {
        return count($this->cities);
    }
}
