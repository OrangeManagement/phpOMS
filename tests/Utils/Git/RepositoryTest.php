<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Utils\Git;

use phpOMS\Utils\Git\Repository;

class RepositoryTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $repo = new Repository(realpath(__DIR__ . '/../../../'));
        self::assertTrue('phpOMS' === $repo->getName() || 'build' === $repo->getName());
        self::assertEquals(str_replace('\\', '/', realpath(__DIR__ . '/../../../.git')), str_replace('\\', '/', $repo->getDirectoryPath()));
    }
}