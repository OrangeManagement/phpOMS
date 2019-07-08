<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Stdlib\Base
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Stdlib\Base;

/**
 * Address type enum.
 *
 * @package    phpOMS\Stdlib\Base
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
abstract class AddressType extends Enum
{
    public const HOME     = 1;
    public const BUSINESS = 2;
    public const SHIPPING = 3;
    public const BILLING  = 4;
    public const WORK     = 5;
    public const CONTRACT = 6;
    public const OTHER    = 7;
}
