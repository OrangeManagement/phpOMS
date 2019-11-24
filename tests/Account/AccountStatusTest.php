<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Account;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Account\AccountStatus;

/**
 * @internal
 */
class AccountStatusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, AccountStatus::getConstants());
    }

    /**
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, AccountStatus::ACTIVE);
        self::assertEquals(2, AccountStatus::INACTIVE);
        self::assertEquals(3, AccountStatus::TIMEOUT);
        self::assertEquals(4, AccountStatus::BANNED);
    }
}
