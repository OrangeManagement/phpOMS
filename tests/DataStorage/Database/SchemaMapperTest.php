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
namespace phpOMS\tests\DataStorage\Database;

use phpOMS\DataStorage\Database\SchemaMapper;

/**
 * @testdox phpOMS\tests\DataStorage\Database\SchemaMapperTest: Mapper for the database schema
 *
 * @internal
 */
class SchemaMapperTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp() : void
    {
        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_base` (
                `test_base_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_base_string` varchar(254) NOT NULL,
                `test_base_int` int(11) NOT NULL,
                `test_base_bool` tinyint(1) DEFAULT NULL,
                `test_base_null` int(11) DEFAULT NULL,
                `test_base_float` decimal(5, 4) DEFAULT NULL,
                `test_base_belongs_to_one` int(11) DEFAULT NULL,
                `test_base_owns_one_self` int(11) DEFAULT NULL,
                `test_base_json` varchar(254) DEFAULT NULL,
                `test_base_json_serializable` varchar(254) DEFAULT NULL,
                `test_base_datetime` datetime DEFAULT NULL,
                `test_base_datetime_null` datetime DEFAULT NULL, /* There was a bug where it returned the current date because new \DateTime(null) === current date which is wrong, we want null as value! */
                PRIMARY KEY (`test_base_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();

        $GLOBALS['dbpool']->get()->con->prepare(
            'CREATE TABLE `oms_test_belongs_to_one` (
                `test_belongs_to_one_id` int(11) NOT NULL AUTO_INCREMENT,
                `test_belongs_to_one_string` varchar(254) NOT NULL,
                PRIMARY KEY (`test_belongs_to_one_id`)
            )ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;'
        )->execute();
    }

    protected function tearDown() : void
    {
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_base')->execute();
        $GLOBALS['dbpool']->get()->con->prepare('DROP TABLE oms_test_belongs_to_one')->execute();
    }

    /**
     * @testdox The tables can be returned
     * @covers phpOMS\DataStorage\Database\SchemaMapper
     * @group framework
     */
    public function testTables() : void
    {
        $schema = new SchemaMapper($GLOBALS['dbpool']->get());

        self::assertTrue(\in_array('oms_test_base', $schema->getTables()));
        self::assertTrue(\in_array('oms_test_belongs_to_one', $schema->getTables()));
    }

    /**
     * @testdox The fields of a table can be returned
     * @covers phpOMS\DataStorage\Database\SchemaMapper
     * @group framework
     */
    public function testFields() : void
    {
        $schema = new SchemaMapper($GLOBALS['dbpool']->get());

        self::assertEquals(
            12,
            \count($schema->getFields('oms_test_base'))
        );
    }
}