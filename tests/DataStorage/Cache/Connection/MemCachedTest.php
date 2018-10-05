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
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\DataStorage\Cache\Connection;

use phpOMS\DataStorage\Cache\Connection\MemCached;
use phpOMS\DataStorage\Cache\CacheStatus;
use phpOMS\DataStorage\Cache\CacheType;
use phpOMS\Utils\TestUtils;

class MemCachedTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);

        self::assertEquals('', $cache->getPrefix());
        self::assertEquals(CacheType::MEMCACHED, $cache->getType());
        self::assertTrue($cache->flushAll());
        self::assertEquals(0, $cache->getThreshold());
        self::assertEquals(null, $cache->get('test'));
    }

    public function testConnect()
    {
        $cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);

        self::assertEquals(CacheStatus::OK, $cache->getStatus());
        self::assertEquals($GLOBALS['CONFIG']['cache']['memcached']['host'], $cache->getHost());
        self::assertEquals((int) $GLOBALS['CONFIG']['cache']['memcached']['port'], $cache->getPort());
    }

    public function testGetSet()
    {
        $cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);

        $cache->flushAll();

        $cache->set('key1', 'testVal'); // 1
        self::assertEquals('testVal', $cache->get('key1'));

        self::assertTrue($cache->add('addKey', 'testValAdd')); // 2
        self::assertFalse($cache->add('addKey', 'testValAdd2'));
        self::assertEquals('testValAdd', $cache->get('addKey'));

        $cache->set('key2', false); // 3
        self::assertEquals(false, $cache->get('key2'));

        $cache->set('key3', null); // 4
        self::assertEquals(null, $cache->get('key3'));

        $cache->set('key4', 4); // 5
        self::assertEquals(4, $cache->get('key4'));

        $cache->set('key5', 5.12); // 6
        self::assertEquals(5.12, $cache->get('key5'));

        $cache->set('key6', ['asdf', 1, true, 2.3]); // 7
        self::assertEquals(json_encode(['asdf', 1, true, 2.3]), $cache->get('key6'));

        self::assertTrue($cache->replace('key4', 5));
        self::assertFalse($cache->replace('keyInvalid', 5));
        self::assertEquals(5, $cache->get('key4'));

        self::assertTrue($cache->delete('key4')); // 6
        self::assertFalse($cache->delete('keyInvalid'));
        self::assertEquals(null, $cache->get('key4'));

        self::assertArraySubset(
            [
                'status'  => CacheStatus::OK,
                'count'   => 6,
            ],
            $cache->stats()
        );

        self::assertTrue($cache->flushAll());
        self::assertTrue($cache->flush());
        self::assertEquals(null, $cache->get('key5')); // This reduces the stat count by one see stat comment. Stupid memcached!

        $cache->flushAll();

        self::assertArraySubset(
            [
                'status'  => CacheStatus::OK,
                'count'   => 5, // Carefull memcached is dumb and keeps expired elements which were not acessed after flushing in stats
            ],
            $cache->stats()
        );
    }

    public function testBadCacheStatus()
    {
        $cache = new MemCached($GLOBALS['CONFIG']['cache']['memcached']);
        $cache->flushAll();

        TestUtils::setMember($cache, 'status', CacheStatus::FAILURE);

        $cache->set('key1', 'testVal');
        self::assertFalse($cache->add('key2', 'testVal2'));
        self::assertEquals(null, $cache->get('key1'));
        self::assertFalse($cache->replace('key1', 5));
        self::assertFalse($cache->delete('key1'));
        self::assertFalse($cache->flushAll());
        self::assertFalse($cache->flush());
        self::assertEquals([], $cache->stats());
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCacheHost()
    {
        $db = $GLOBALS['CONFIG']['cache']['memcached'];
        unset($db['host']);

        $cache = new MemCached($db);
    }

    /**
     * @expectedException \phpOMS\DataStorage\Cache\Exception\InvalidConnectionConfigException
     */
    public function testInvalidCachePort()
    {
        $db = $GLOBALS['CONFIG']['cache']['memcached'];
        unset($db['port']);

        $cache = new MemCached($db);
    }
}