<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Cache\Exception
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Cache\Exception;

/**
 * Permission exception class.
 *
 * @package    phpOMS\DataStorage\Cache\Exception
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class InvalidConnectionConfigException extends \InvalidArgumentException
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
    public function __construct(string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct('Missing config value for "' . $message . '".', $code, $previous);
    }
}