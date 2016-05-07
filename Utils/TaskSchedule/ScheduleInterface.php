<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\TaskSchedule;

/**
 * Array utils.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface ScheduleInterface
{
    public function add(TaskInterface $task);

    public function remove($id);

    public function get(string $id);

    public function list();

    public function set(TaskInterface $task);

    public function save();

    public function run(string $cmd);
}
