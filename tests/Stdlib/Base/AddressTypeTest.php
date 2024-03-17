<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\AddressType;

/**
 * @internal
 */
final class AddressTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(7, AddressType::getConstants());
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(AddressType::getConstants(), \array_unique(AddressType::getConstants()));
    }

    /**
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, AddressType::HOME);
        self::assertEquals(2, AddressType::BUSINESS);
        self::assertEquals(3, AddressType::SHIPPING);
        self::assertEquals(4, AddressType::BILLING);
        self::assertEquals(5, AddressType::WORK);
        self::assertEquals(8, AddressType::EDUCATION);
        self::assertEquals(99, AddressType::OTHER);
    }
}
