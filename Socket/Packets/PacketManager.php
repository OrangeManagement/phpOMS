<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Socket\Packets
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Socket\Packets;

use phpOMS\Socket\CommandManager;
use phpOMS\Socket\Server\ClientManager;

/**
 * Server class.
 *
 * Parsing/serializing arrays to and from php file
 *
 * @package    phpOMS\Socket\Packets
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class PacketManager
{

    /**
     * Command Manager.
     *
     * @var CommandManager
     * @since 1.0.0
     */
    private $commandManager = null;

    /**
     * Client Manager.
     *
     * @var ClientManager
     * @since 1.0.0
     */
    private $clientManager = null;

    /**
     * Constructor.
     *
     * @param CommandManager $cmd  Command Manager
     * @param ClientManager  $user Client Manager
     *
     * @since  1.0.0
     */
    public function __construct(CommandManager $cmd, ClientManager $user)
    {
        $this->commandManager = $cmd;
        $this->clientManager  = $user;
    }

    /**
     * Handle package.
     *
     * @param string $data Package data
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function handle(string $data, $client) : void
    {
        echo $data;
    }
}
