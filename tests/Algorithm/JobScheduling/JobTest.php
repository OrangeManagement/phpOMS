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

namespace phpOMS\tests\Algorithm\JobScheduling;

use phpOMS\Algorithm\JobScheduling\Job;

/**
 * @testdox phpOMS\tests\Algorithm\JobScheduling\JobTest: Default job for the job scheduling
 *
 * @internal
 */
class JobTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The job has the expected values after initialization
     * @covers phpOMS\Algorithm\JobScheduling\Job
     * @group framework
     */
    public function testDefault() : void
    {
        $item = new Job(3.0, new \DateTime('now'), null, 'abc');

        self::assertEquals(3.0, $item->getValue());
        self::assertEquals((new \DateTime('now'))->format('Y-m-d'), $item->getStart()->format('Y-m-d'));
        self::assertEquals(null, $item->getEnd());
        self::assertEquals('abc', $item->getName());
    }
}