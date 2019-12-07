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

namespace phpOMS\tests\Router;

use Modules\Admin\Controller\BackendController;
use Modules\Admin\Models\PermissionState;
use phpOMS\Account\Account;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Router\SocketRouter;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Router\SocketRouterTest: Router for socket requests
 *
 * @internal
 */
class SocketRouterTest extends \PHPUnit\Framework\TestCase
{
    protected SocketRouter $router;

    protected function setUp() : void
    {
        $this->router = new SocketRouter();
    }

    /**
     * @testdox The route result for an empty request is empty
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEmpty($this->router->route('some_test route'));
    }

    /**
     * @testdox A none-existing routing file cannot be imported
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testInvalidRoutingFile() : void
    {
        self::assertFalse($this->router->importFromFile(__Dir__ . '/invalidFile.php'));
    }

    /**
     * @testdox A existing routing file can be imported
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testLoadingRoutesFromFile() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/socketRouterTestFile.php'));
    }

    /**
     * @testdox A matching route returns the destinations
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/socketRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123')
        );
    }

    /**
     * @testdox Routes can be added dynamically
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDynamicRouteAdding() : void
    {
        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backends_admin -settings=general -t 123')
        );

        $this->router->add('^.*backends_admin -settings=general.*$', 'Controller:test');
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123')
        );
    }

    /**
     * @testdox Routes only match if the permissions match
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testWithValidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/socketRouterTestFilePermission.php'));

        $perm = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account = new Account();
        $account->addPermission($perm);

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account
            )
        );
    }

    /**
     * @testdox Routes don't match if the permissions don't match
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testWithInvalidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/socketRouterTestFilePermission.php'));

        $perm2 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::CREATE
        ) extends PermissionAbstract {};

        $perm3 = new class(
            null,
            null,
            'InvalidModule',
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $perm4 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            99,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account2 = new Account();
        $account2->addPermission($perm2);
        $account2->addPermission($perm3);
        $account2->addPermission($perm4);

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account2
            )
        );
    }
}