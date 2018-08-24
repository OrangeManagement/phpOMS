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
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Socket\Packets;

use phpOMS\Stdlib\Base\Enum;

/**
 * Packet type enum.
 *
 * @package    phpOMS\Socket\Packets
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class PacketType extends Enum
{
    public const CONNECT    = 0; /* Client connection (server/sender) */
    public const DISCONNECT = 1; /* Client disconnection (server/sender) */
    public const KICK       = 2; /* Kick (server/client/sender) */
    public const PING       = 3; /* Ping (server/sender) */
    public const HELP       = 4; /* Help (server/sender) */
    public const RESTART    = 5; /* Restart server (server/all clients/client) */
    public const MSG        = 6; /* Message (server/sender/client/all clients?) */
    public const LOGIN      = 7; /* Login (server/sender) */
    public const LOGOUT     = 8; /* Logout (server/sender) */
    public const CMD        = 9; /* Other command */
    public const MODULE     = 999999999; /* Module packet ??? */
}
