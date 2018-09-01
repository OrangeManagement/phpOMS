<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Localization\Defaults;

require_once __DIR__ . '/../../Autoloader.php';

use phpOMS\Localization\Defaults\Language;
use phpOMS\Localization\Defaults\LanguageMapper;
use phpOMS\DataStorage\Database\DataMapperAbstract;
use phpOMS\DataStorage\Database\Connection\SQLiteConnection;

class LanguageMapperTest extends \PHPUnit\Framework\TestCase
{
    static function setUpBeforeClass() 
    {
        $con = new SqliteConnection([
            'prefix' => '',
            'db'     => 'sqlite',
            'path'   => realpath(__DIR__ . '/../../../Localization/Defaults/localization.sqlite'),
        ]);

        DataMapperAbstract::setConnection($con);
    }

    public function testR()
    {
        $obj = LanguageMapper::get(53);
        self::assertEquals('German', $obj->getName());
        self::assertEquals('Deutsch', $obj->getNative());
        self::assertEquals('de', $obj->getCode2());
        self::assertEquals('deu', $obj->getCode3Native());
        self::assertEquals('ger', $obj->getCode3());
    }

    static function tearDownAfterClass() 
    {
        DataMapperAbstract::setConnection($GLOBALS['dbpool']->get());
    }
}