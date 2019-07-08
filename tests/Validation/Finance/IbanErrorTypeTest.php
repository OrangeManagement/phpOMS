<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Validation\Finance;

use phpOMS\Validation\Finance\IbanErrorType;

/**
 * @internal
 */
class IbanErrorTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(5, IbanErrorType::getConstants());
        self::assertEquals(1, IbanErrorType::INVALID_COUNTRY);
        self::assertEquals(2, IbanErrorType::INVALID_LENGTH);
        self::assertEquals(4, IbanErrorType::INVALID_CHECKSUM);
        self::assertEquals(8, IbanErrorType::EXPECTED_ZERO);
        self::assertEquals(16, IbanErrorType::EXPECTED_NUMERIC);
    }
}
