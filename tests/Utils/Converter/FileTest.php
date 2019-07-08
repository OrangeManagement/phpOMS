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

namespace phpOMS\tests\Utils\Converter;

use phpOMS\Utils\Converter\File;

/**
 * @internal
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    public function testByteSizeToString() : void
    {
        self::assertEquals('400b', File::byteSizeToString(400));
        self::assertEquals('5kb', File::byteSizeToString(5000));
        self::assertEquals('7mb', File::byteSizeToString(7000000));
        self::assertEquals('1.5gb', File::byteSizeToString(1500000000));
    }

    public function testKilobyteSizeToString() : void
    {
        self::assertEquals('500kb', File::kilobyteSizeToString(500));
        self::assertEquals('5mb', File::kilobyteSizeToString(5000));
        self::assertEquals('5.4gb', File::kilobyteSizeToString(5430000));
    }
}
