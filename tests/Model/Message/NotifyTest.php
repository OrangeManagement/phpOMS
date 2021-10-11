<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package    tests
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\phpOMS\Model\Message;

use phpOMS\Model\Message\Notify;
use phpOMS\Model\Message\NotifyType;

/**
 * @internal
 */
class NotifyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers phpOMS\Model\Message\Notify
     * @group framework
     */
    public function testAttributes() : void
    {
        $obj = new Notify();
        self::assertInstanceOf('\phpOMS\Model\Message\Notify', $obj);

        /* Testing members */
        self::assertObjectHasAttribute('delay', $obj);
        self::assertObjectHasAttribute('title', $obj);
        self::assertObjectHasAttribute('stay', $obj);
        self::assertObjectHasAttribute('message', $obj);
        self::assertObjectHasAttribute('level', $obj);
    }

    /**
     * @covers phpOMS\Model\Message\Notify
     * @group framework
     */
    public function testDefault() : void
    {
        $obj = new Notify();

        /* Testing default values */
        self::assertEquals(0, $obj->toArray()['time']);
        self::assertEquals('', $obj->toArray()['title']);
        self::assertEquals('', $obj->toArray()['msg']);
        self::assertEquals(0, $obj->toArray()['stay']);
        self::assertEquals(NotifyType::INFO, $obj->toArray()['level']);
    }

    /**
     * @covers phpOMS\Model\Message\Notify
     * @group framework
     */
    public function testSetGet() : void
    {
        $obj = new Notify('message', NotifyType::WARNING);
        $obj->setDelay(3);
        $obj->setStay(5);
        $obj->setLevel(NotifyType::ERROR);
        $obj->setMessage('msg');
        $obj->setTitle('title');

        self::assertEquals([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotifyType::ERROR,
        ], $obj->toArray());

        self::assertEquals(json_encode([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotifyType::ERROR,
        ]), $obj->serialize());

        self::assertEquals([
            'type'  => 'notify',
            'time'  => 3,
            'stay'  => 5,
            'msg'   => 'msg',
            'title' => 'title',
            'level' => NotifyType::ERROR,
        ], $obj->jsonSerialize());

        $obj2 = new Notify();
        $obj2->unserialize($obj->serialize());
        self::assertEquals($obj, $obj2);
    }
}
