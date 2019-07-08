<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Schema\Exception
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Exception;

/**
 * Path exception class.
 *
 * @package    phpOMS\DataStorage\Database\Schema\Exception
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class TableException extends \PDOException
{
    /**
     * Constructor.
     *
     * @param string     $message  Exception message
     * @param int        $code     Exception code
     * @param \Exception $previous Previous exception
     *
     * @since  1.0.0
     */
    public function __construct(string $message, int $code = 0, \Exception $previous = null)
    {
        parent::__construct('The table "' . $message . '" doesn\'t exist.', $code, $previous);
    }

    /**
     * Get table name from exception.
     *
     * @param string $message Exception message
     *
     * @return string
     *
     * @since  1.0.0
     */
    public static function findTable(string $message) : string
    {
        $pos1 = \strpos($message, '\'');

        if ($pos1 === false) {
            return $message;
        }

        $pos2 = \strpos($message, '\'', $pos1 + 1);

        if ($pos2 === false) {
            return $message;
        }

        $table = \substr($message, $pos1 + 1, $pos2 - $pos1 - 1);

        return $table === false ? $message : $table;
    }
}
