<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\System\File;

use phpOMS\System\File\PathException;

class PathExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor()
    {
        $e = new PathException('test.file');
        self::assertContains('test.file', $e->getMessage());
        self::assertEquals(0, $e->getCode());
        $this->isInstanceOf('\UnexpectedValueException');
    }
}