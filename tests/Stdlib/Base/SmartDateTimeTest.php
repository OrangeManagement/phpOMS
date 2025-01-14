<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\SmartDateTime;

require_once __DIR__ . '/../../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\SmartDateTime::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\SmartDateTimeTest: DateTime type with additional functionality')]
final class SmartDateTimeTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The smart datetime extends the datetime')]
    public function testAttributes() : void
    {
        $datetime = new SmartDateTime();
        self::assertInstanceOf('\DateTime', $datetime);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The smart datetime can be formatted like the datetime')]
    public function testFormat() : void
    {
        $datetime = new SmartDateTime('1970-01-01');
        self::assertEquals('1970-01-01', $datetime->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The smart datetime can be modified an creates a new smart datetime')]
    public function testCreateModify() : void
    {
        $datetime = new SmartDateTime('1970-01-01');
        $new      = $datetime->createModify(1, 1, 1);

        self::assertEquals('1970-01-01', $datetime->format('Y-m-d'));
        self::assertEquals('1971-02-02', $new->format('Y-m-d'));

        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals('1976-07-01', $datetime->createModify(0, 13)->format('Y-m-d'));
        self::assertEquals('1976-01-01', $datetime->createModify(0, 7)->format('Y-m-d'));
        self::assertEquals('1975-03-01', $datetime->createModify(0, -3)->format('Y-m-d'));
        self::assertEquals('1974-11-01', $datetime->createModify(0, -7)->format('Y-m-d'));
        self::assertEquals('1973-11-01', $datetime->createModify(0, -19)->format('Y-m-d'));
        self::assertEquals('1973-12-01', $datetime->createModify(0, -19, 30)->format('Y-m-d'));
        self::assertEquals('1973-12-31', $datetime->createModify(0, -18, 30)->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The days of the month can be returned')]
    public function testDaysOfMonth() : void
    {
        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals(30, $datetime->getDaysOfMonth());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The week day index of the first day of the month can be returned')]
    public function testFirstDayOfMonth() : void
    {
        $datetime = new SmartDateTime('1975-06-01');
        self::assertEquals(0, $datetime->getFirstDayOfMonth());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be created from a datetime')]
    public function testCreateFromDateTime() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);
        self::assertEquals($expected->format('Y-m-d H:i:s'), $obj->format('Y-m-d H:i:s'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the last day of the month')]
    public function testEndOfMonth() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date("Y-m-t", \strtotime($expected->format('Y-m-d'))), $obj->getEndOfMonth()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the fist day of the month')]
    public function testStartOfMonth() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date("Y-m-01", \strtotime($expected->format('Y-m-d'))), $obj->getStartOfMonth()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the last day of the week')]
    public function testEndOfWeek() : void
    {
        $expected = new \DateTime('2019-11-23');
        $obj      = new SmartDateTime('2019-11-21');

        self::assertEquals($expected->format('Y-m-d'), $obj->getEndOfWeek()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the fist day of the week')]
    public function testStartOfWeek() : void
    {
        $expected = new \DateTime('2019-11-17');
        $obj      = new SmartDateTime('2019-11-21');

        self::assertEquals($expected->format('Y-m-d'), $obj->getStartOfWeek()->format('Y-m-d'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the end of the day')]
    public function testEndOfDay() : void
    {
        $expected = new \DateTime('2019-11-21');
        $obj      = new SmartDateTime('2019-11-21');

        self::assertEquals($expected->format('Y-m-d')  . ' 23:59:59', $obj->getEndOfDay()->format('Y-m-d H:i:s'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A smart datetime can be returned of the start of the day')]
    public function testStartOfDay() : void
    {
        $expected = new \DateTime('2019-11-21');
        $obj      = new SmartDateTime('2019-11-21');

        self::assertEquals($expected->format('Y-m-d')  . ' 00:00:00', $obj->getStartOfDay()->format('Y-m-d H:i:s'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A date or year can be checked if it is a leap year')]
    public function testLeapYear() : void
    {
        self::assertFalse((new SmartDateTime('2103-07-20'))->isLeapYear());
        self::assertTrue((new SmartDateTime('2104-07-20'))->isLeapYear());
        self::assertFalse(SmartDateTime::leapYear(2103));
        self::assertTrue(SmartDateTime::leapYear(2104));
        self::assertFalse(SmartDateTime::leapYear(1900));
        self::assertTrue(SmartDateTime::leapYear(1600));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The day of the week index can be returned from a date')]
    public function testDayOfWeek() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertEquals(\date('w', $expected->getTimestamp()), SmartDateTime::dayOfWeek((int) $expected->format('Y'), (int) $expected->format('m'), (int) $expected->format('d')));
        self::assertEquals(\date('w', $expected->getTimestamp()), $obj->getDayOfWeek());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid day of the week returns a negative week index')]
    public function testInvalidDayOfWeek() : void
    {
        self::assertEquals(-1, SmartDateTime::dayOfWeek(-2, 0, 99));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A calendar sheet is returned containing all days of the month and some days of the previous and next month')]
    public function testCalendarSheet() : void
    {
        $expected = new \DateTime('now');
        $obj      = SmartDateTime::createFromDateTime($expected);

        self::assertCount(42, $obj->getMonthCalendar());
        self::assertCount(42, $obj->getMonthCalendar(1));
    }
}
