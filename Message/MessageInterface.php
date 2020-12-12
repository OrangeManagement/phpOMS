<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Message
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Message;

/**
 * Message interface.
 *
 * @package phpOMS\Message
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
interface MessageInterface
{
    /**
    * Gets the body of the message.
    *
    * @return string
    *
    * @since 1.0.0
    */
    public function getBody() : string;
}
