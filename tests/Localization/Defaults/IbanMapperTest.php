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

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\DataStorage\Database\Connection\SQLiteConnection;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\Localization\Defaults\IbanMapper;

/**
 * @testdox phpOMS\tests\Localization\Defaults\IbanMapperTest: Iban database mapper
 *
 * @internal
 */
class IbanMapperTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass() : void
    {
        $con = new SqliteConnection([
            'prefix'     => '',
            'db'         => 'sqlite',
            'database'   => \realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    /**
     * @testdox The model can be read from the database
     * @group framework
     */
    public function testR() : void
    {
        $obj = IbanMapper::get(22);
        self::assertEquals('DE', $obj->getCountry());
        self::assertEquals(22, $obj->getChars());
        self::assertEquals('18n', $obj->getBban());
        self::assertEquals('DEkk bbbb bbbb cccc cccc cc', $obj->getFields());
    }

    public static function tearDownAfterClass() : void
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}
