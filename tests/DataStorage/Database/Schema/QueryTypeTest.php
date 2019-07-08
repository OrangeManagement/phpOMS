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

namespace phpOMS\tests\DataStorage\Database\Schema;

use phpOMS\DataStorage\Database\Schema\QueryType;

/**
 * @internal
 */
class QueryTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(13, QueryType::getConstants());
        self::assertEquals(QueryType::getConstants(), \array_unique(QueryType::getConstants()));

        self::assertEquals(128, QueryType::DROP_DATABASE);
        self::assertEquals(129, QueryType::ALTER);
        self::assertEquals(130, QueryType::TABLES);
        self::assertEquals(131, QueryType::FIELDS);
        self::assertEquals(132, QueryType::CREATE_TABLE);
        self::assertEquals(133, QueryType::DROP_TABLE);
    }
}
