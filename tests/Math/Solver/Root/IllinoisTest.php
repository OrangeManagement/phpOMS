<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Solver\Root;

use phpOMS\Math\Solver\Root\Illinois;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Solver\Root\IllinoisTest: Various math functions')]
final class IllinoisTest extends \PHPUnit\Framework\TestCase
{
    public function testRoot() : void
    {
        self::assertEqualsWithDelta(
            1.521,
            Illinois::root(function($x) { return $x * $x * $x - $x - 2; }, 1, 2),
            0.1
        );
    }
}
