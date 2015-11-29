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
namespace phpOMS\Log;

interface LoggerInterface
{

    /**
     * System is unusable.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function emergency(\string $message, array $context = []);

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function alert(\string $message, array $context = []);

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function critical(\string $message, array $context = []);

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function error(\string $message, array $context = []);

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function warning(\string $message, array $context = []);

    /**
     * Normal but significant events.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function notice(\string $message, array $context = []);

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function info(\string $message, array $context = []);

    /**
     * Detailed debug information.
     *
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function debug(\string $message, array $context = []);

    /**
     * Logs with an arbitrary level.
     *
     * @param \string $level
     * @param \string $message
     * @param array $context
     *
     * @return null
     */
    public function log(\string $level, \string $message, array $context = []);
}
