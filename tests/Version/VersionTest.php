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

namespace phpOMS\tests\Version;

use phpOMS\Version\Version;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class VersionTest extends \PHPUnit\Framework\TestCase
{
    public function testVersionCompare() : void
    {
        $version1 = '1.23.456';
        $version2 = '1.23.567';

        self::assertEquals(Version::compare($version1, $version1), 0);
        self::assertEquals(Version::compare($version1, $version2), -1);
        self::assertEquals(Version::compare($version2, $version1), 1);
    }
}
