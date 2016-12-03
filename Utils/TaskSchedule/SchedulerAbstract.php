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
 * Scheduler abstract.
 *
 * @category   Framework
 * @package    phpOMS\Utils\TaskSchedule
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class SchedulerAbstract
{
    /**
     * Tasks.
     *
     * @var TaskAbstract[]
     * @since 1.0.0
     */
    protected $tasks = [];

    /**
     * Bin path.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $bin = '';

    /**
     * Get git binary.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getBin() : string
    {
        return self::$bin;
    }

    /**
     * Set git binary.
     *
     * @param string $path Git path
     *
     * @throws PathException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function setBin(string $path) /* : void */
    {
        if (realpath($path) === false) {
            throw new PathException($path);
        }

        self::$bin = realpath($path);
    }

    /**
     * Test git.
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function test() : bool
    {
        $pipes    = [];
        $resource = proc_open(escapeshellarg(self::$bin), [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        foreach ($pipes as $pipe) {
            fclose($pipe);
        }

        return trim(proc_close($resource)) !== 127;
    }

    /**
     * Add task
     *
     * @param TaskAbstract $task Task to add
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function add(TaskAbstract $task) /* : void */
    {
        $this->tasks[$task->getId()] = $task;
    }

    /**
     * Remove task
     *
     * @param mixed $id Task id
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function remove(string $id) : bool
    {
        if (isset($this->tasks[$id])) {
            unset($this->tasks[$id]);

            return true;
        }

        return false;
    }

    /**
     * Get task
     *
     * @param mixed $id Task id
     *
     * @return TaskAbstract|null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get(string $id)
    {
        return $this->tasks[$id] ?? null;
    }

    /**
     * Get all tasks
     *
     * @return TaskAbstract[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getAll() : array
    {
        return $this->tasks;
    }

    /**
     * Set task
     *
     * @param TaskAbstract $task Task to edit
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function set(TaskAbstract $task) /* : void */
    {
        $this->tasks[$task->getId()] = $task;
    }

    /**
     * Save tasks
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    abstract public function save() /* : void */;
}
