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

namespace phpOMS\tests\Message\Http;

use phpOMS\Message\Http\OSType;

/**
 * @internal
 */
class OSTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(24, OSType::getConstants());
    }

    /**
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(OSType::getConstants(), \array_unique(OSType::getConstants()));
    }

}
