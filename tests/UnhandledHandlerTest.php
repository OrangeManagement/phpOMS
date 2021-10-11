<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests;

use phpOMS\UnhandledHandler;

/**
 * @internal
 */
class UnhandledHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\UnhandledHandler
     */
    public function testErrorHandling() : void
    {
        \set_exception_handler(['\phpOMS\UnhandledHandler', 'exceptionHandler']);
        \set_error_handler(['\phpOMS\UnhandledHandler', 'errorHandler']);
        \register_shutdown_function(['\phpOMS\UnhandledHandler', 'shutdownHandler']);

        \trigger_error('', \E_USER_ERROR);

        UnhandledHandler::shutdownHandler();

        self::assertFalse(UnhandledHandler::errorHandler(0, '', '', 0));
    }
}
