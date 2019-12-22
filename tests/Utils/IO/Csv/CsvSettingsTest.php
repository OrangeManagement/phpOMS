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

namespace phpOMS\tests\Utils\IO\Csv;

use phpOMS\Utils\IO\Csv\CsvSettings;

/**
 * @testdox phpOMS\tests\Utils\IO\Csv\CsvSettingsTest: Csv file settings
 *
 * @internal
 */
class CsvSettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The delimiter in a csv file can be guessed
     * @covers phpOMS\Utils\IO\Csv\CsvSettings
     * @group framework
     */
    public function testDelimiter() : void
    {
        self::assertEquals(':', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/colon.csv', 'r')));
        self::assertEquals(',', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/comma.csv', 'r')));
        self::assertEquals('|', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/pipe.csv', 'r')));
        self::assertEquals(';', CsvSettings::getFileDelimiter(\fopen(__DIR__ . '/semicolon.csv', 'r')));
    }
}
