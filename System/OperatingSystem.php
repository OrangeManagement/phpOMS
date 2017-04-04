<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\System;

/**
 * Operating system class.
 *
 * @category   Framework
 * @package    phpOMS\System
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
final class OperatingSystem
{
    /**
     * Get OS.
     *
     * @return int|SystemType
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getSystem() : int
    {
        if(stristr(PHP_OS, 'DAR') !== false) {
            return SystemType::OSX;
        } elseif(stristr(PHP_OS, 'WIN') !== false) {
            return SystemType::WIN;
        } elseif(stristr(PHP_OS, 'LINIX') !== false) {
            return SystemType::LINUX;
        } 

        return SystemType::UNKNOWN;
    }
}