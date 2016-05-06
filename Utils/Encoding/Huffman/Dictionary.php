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
namespace phpOMS\Utils\Encoding\Huffman;

/**
 * Gray encoding class
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
final class Dictionary
{
    /**
     * Huffman dictionary.
     *
     * @var array
     * @since 1.0.0
     */
    private $dictionary = [];

    /**
     * Minimum length.
     *
     * @var int
     * @since 1.0.0
     */
    private $min = -1;

    /**
     * Maximum length.
     *
     * @var int
     * @since 1.0.0
     */
    private $max = -1;

    /**
     * Constructor.
     *
     * @param string $source Source to create the dictionary from
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $source = '')
    {
        if (isset($source)) {
            $this->generate($source);
        }
    }

    /**
     * Get dictionary value by entry
     *
     * @param string $entry 1 character entry
     *
     * @return string
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function get(string $entry) : string
    {
        if (strlen($entry) !== 1) {
            throw new \Exception('Must be a character.');
        }

        if (!isset($this->dictionary[$entry])) {
            throw new \Exception('Character does not exist');
        }

        return $this->dictionary[$entry];
    }

    /**
     * Get dictionary entry and reduce value
     *
     * @param string $value Dictionary value
     *
     * @return null|string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEntry(&$value)
    {
        $length = strlen($value);
        if ($length < $this->min) {
            return null;
        }

        for ($i = $this->min; $i <= $this->max; ++$i) {
            $needle = substr($value, 0, $i);

            foreach ($this->dictionary as $key => $val) {
                if ($needle === $val) {
                    $value = substr($value, $i);

                    return $key;
                }
            }
        }

        return null;
    }

    /**
     * Set dictionary value
     *
     * @param string $entry 1 character entry
     * @param string $value Dictionary value
     *
     * @return void
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function set(string $entry, string $value)
    {
        if (strlen($entry) !== 1) {
            throw new \Exception('Must be a character.');
        }

        if (!isset($this->dictionary[$entry])) {
            throw new \Exception('Character does not exist');
        }

        if (strlen(str_replace('0', '', str_replace('1', '', $value))) !== 0) {
            throw new \Exception('Bad formatting.');
        }

        $length = strlen($value);

        if ($this->min === -1 || $length < $this->min) {
            $this->min = $length;
        }

        if ($this->max === -1 || $length > $this->max) {
            $this->max = $length;
        }

        $this->dictionary[$entry] = $value;
    }

    /**
     * Generate dictionary from data.
     *
     * @param string $source Source data to generate dictionary from
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function generate(string $source)
    {
        $this->dictionary = [];
        $this->min        = -1;
        $this->max        = -1;

        $count = [];
        while (isset($source[0])) {
            $count[] = [substr_count($source, $source[0]), $source[0]];
            $source  = str_replace($source[0], '', $source);
        }

        sort($count);

        while (count($count) > 1) {
            $row1    = array_shift($count);
            $row2    = array_shift($count);
            $count[] = [$row1[0] + $row2[0], [$row1, $row2]];

            sort($count);
        }

        $this->fill(is_array($count[0][1]) ? $count[0][1] : $count);
    }

    /**
     * Fill dictionary.
     *
     * @param string $entry Source data to generate dictionary from
     * @param string $value Dictionary value
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function fill(string $entry, string $value = '')
    {
        if (!is_array($entry[0][1])) {
            $this->set($entry[0][1], $value . '0');
        } else {
            $this->fill($entry[0][1], $value . '0');
        }

        if (isset($entry[1])) {
            if (!is_array($entry[1][1])) {
                $this->set($entry[1][1], $value . '1');
            } else {
                $this->fill($entry[1][1], $value . '1');
            }
        }
    }
}