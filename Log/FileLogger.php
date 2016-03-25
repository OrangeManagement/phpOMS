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
namespace phpOMS\Log;

use phpOMS\Datatypes\Exception\InvalidEnumValue;
use phpOMS\System\File\PathException;
use phpOMS\Validation\Validator;

/**
 * Logging class.
 *
 * @category   Framework
 * @package    phpOMS\Log
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class FileLogger implements LoggerInterface
{
    const MSG_BACKTRACE = '{datetime}; {level}; {ip}; {message}; {backtrace}';
    const MSG_FULL      = '{datetime}; {level}; {ip}; {line}; {version}; {os}; {path}; {message}; {file}; {backtrace}';
    const MSG_SIMPLE    = '{datetime}; {level}; {ip}; {message};';

    /**
     * Timing array.
     *
     * Potential values are null or an array filled with log timings.
     * This is used in order to profile code sections by ID.
     *
     * @var array[float]
     * @since 1.0.0
     */
    public $timings = [];

    /**
     * Instance.
     *
     * @var \phpOMS\DataStorage\Cache\Pool
     * @since 1.0.0
     */
    protected static $instance = null;

    /**
     * Verbose.
     *
     * @var bool
     * @since 1.0.0
     */
    protected static $verbose = false;

    /**
     * The file pointer for the logging.
     *
     * Potential values are null or a valid file pointer
     *
     * @var resource
     * @since 1.0.0
     */
    private $fp = null;

    /**
     * Logging path
     *
     * @var string
     * @since 1.0.0
     */
    private $path = '';

    /**
     * Object constructor.
     *
     * Creates the logging object and overwrites all default values.
     *
     * @param string $lpath Path for logging
     * @param bool $verbose Verbose logging
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct(string $lpath, bool $verbose = false)
    {
        $path = realpath($lpath);
        self::$verbose = $verbose;

        if ($path !== false && Validator::startsWith($path, ROOT_PATH) === false) {
            throw new PathException($lpath);
        }

        if (is_dir($lpath)) {
            if (!file_exists($lpath)) {
                mkdir($lpath, 0644, true);
            }

            if (!file_exists($lpath . '/' . date('Y-m-d') . '.log')) {
                touch($lpath . '/' . date('Y-m-d') . '.log');
            }

            $path = realpath($lpath . '/' . date('Y-m-d') . '.log');
        } else {
            if (!file_exists($lpath)) {
                touch($lpath);
            }

            $path = realpath($lpath);
        }

        $this->path = $path;
    }

    /**
     * Returns instance.
     *
     * @param string $path Logging path
     *
     * @return self
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function getInstance(string $path = '')
    {
        if (self::$instance === null) {
            self::$instance = new self($path);
        }

        return self::$instance;
    }

    /**
     * Object destructor.
     *
     * Closes the logging file
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __destruct()
    {
        if (is_resource($this->fp)) {
            fclose($this->fp);
        }
    }

    /**
     * Protect instance from getting copied from outside.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __clone()
    {
    }

    /**
     * Starts the time measurement.
     *
     * @param string $id the ID by which this time measurement gets identified
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function startTimeLog($id = '')
    {
        $mtime = explode(' ', microtime());
        $mtime = $mtime[1] + $mtime[0];

        $this->timings[$id] = ['start' => $mtime];
    }

    /**
     * Ends the time measurement.
     *
     * @param string $id the ID by which this time measurement gets identified
     *
     * @return int the time measurement
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function endTimeLog($id = '')
    {
        $mtime = explode(' ', microtime());
        $mtime = $mtime[1] + $mtime[0];

        $this->timings[$id]['end']  = $mtime;
        $this->timings[$id]['time'] = $mtime - $this->timings[$id]['start'];

        return $this->timings[$id]['time'];
    }

    /**
     * Sorts timings descending.
     *
     * @param array [float] &$timings the timing array to sort
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function timingSort(&$timings)
    {
        uasort($timings, [$this, 'orderSort']);
    }

    /**
     * Interpolate context
     *
     * @param string $message
     * @param array  $context
     * @param string $level
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function interpolate(string $message, array $context = [], string $level = LogLevel::DEBUG)
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        $backtrace = debug_backtrace();

        // Removing sensitive config data from logging
        foreach ($backtrace as $key => $value) {
            if (isset($value['args'])) {
                unset($backtrace[$key]['args']);
            }
        }

        $backtrace = json_encode($backtrace);

        $replace['{backtrace}'] = str_replace(str_replace('\\', '\\\\', ROOT_PATH), '', $backtrace);
        $replace['{datetime}']  = sprintf('%--19s', (new \DateTime('NOW'))->format('Y-m-d H:i:s'));
        $replace['{level}']     = sprintf('%--12s', $level);
        $replace['{path}']      = $_SERVER['REQUEST_URI'] ?? 'REQUEST_URI';
        $replace['{ip}']        = sprintf('%--15s', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $replace['{version}']   = sprintf('%--15s', PHP_VERSION);
        $replace['{os}']        = sprintf('%--15s', PHP_OS);

        return strtr($message, $replace);
    }

    /**
     * Sorts all timings descending.
     *
     * @param array $a
     * @param array $b
     *
     * @return bool the comparison
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function orderSort($a, $b)
    {
        if ($a['time'] == $b['time']) {
            return 0;
        }

        return ($a['time'] > $b['time']) ? -1 : 1;
    }

    /**
     * Write to file.
     *
     * @param string $message
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function write(string $message)
    {
        $this->fp = fopen($this->path, 'a');
        fwrite($this->fp, $message . "\n");
        fclose($this->fp);

        if(self::$verbose) {
            echo $message . "\n";
        }
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return null
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function emergency(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::EMERGENCY);
        $this->write($message);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function alert(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::ALERT);
        $this->write($message);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function critical(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::CRITICAL);
        $this->write($message);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function error(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::ERROR);
        $this->write($message);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function warning(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::WARNING);
        $this->write($message);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function notice(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::NOTICE);
        $this->write($message);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function info(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::INFO);
        $this->write($message);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function debug(string $message, array $context = [])
    {
        $message = $this->interpolate($message, $context, LogLevel::DEBUG);
        $this->write($message);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function log(string $level, string $message, array $context = [])
    {
        if (!LogLevel::isValidValue($level)) {
            throw new InvalidEnumValue($level);
        }

        $message = $this->interpolate($message, $context, $level);
        $this->write($message);
    }

    /**
     * Analyse logging file.
     *
     * @return array
     */
    public function countLogs()
    {
        $levels = [];

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $line[1] = trim($line[1]);

                if (!isset($levels[$line[1]])) {
                    $levels[$line[1]] = 0;
                }

                $levels[$line[1]]++;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
        }

        return $levels;
    }

    /**
     * Find cricitcal connections.
     *
     * @param int $limit Amout of perpetrators
     *
     * @return array
     */
    public function getHighestPerpetrator(int $limit = 10)
    {
        $connection = [];

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $line[2] = trim($line[2]);

                if (!isset($connection[$line[2]])) {
                    $connection[$line[2]] = 0;
                }

                $connection[$line[2]]++;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
            asort($connection);
        }

        return array_slice($connection, 0, $limit);
    }

    /**
     * Get logging messages from file.
     *
     * @param int $limit  Amout of perpetrators
     * @param int $offset Offset
     *
     * @return array
     */
    public function get(int $limit = 25, int $offset = 0) : array
    {
        $logs = [];
        $id   = 0;

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false) {
                $id++;

                if ($offset > 0) {
                    $offset--;
                    continue;
                }

                if ($limit <= 0) {

                    reset($logs);
                    unset($logs[key($logs)]);
                }

                foreach ($line as &$value) {
                    $value = trim($value);
                }

                $logs[$id] = $line;
                $limit--;
                ksort($logs);
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
        }

        return $logs;
    }

    /**
     * Get single logging message from file.
     *
     * @param int $id Id/Line number of the logging message
     *
     * @return array
     */
    public function getByLine(int $id = 1) : array
    {
        $log     = [];
        $current = 0;

        if (file_exists($this->path)) {
            $this->fp = fopen($this->path, 'r');
            fseek($this->fp, 0);

            while (($line = fgetcsv($this->fp, 0, ';')) !== false && $current <= $id) {
                $current++;

                if ($current < $id) {
                    continue;
                }

                $log['datetime']  = trim($line[0] ?? '');
                $log['level']     = trim($line[1] ?? '');
                $log['ip']        = trim($line[2] ?? '');
                $log['line']      = trim($line[3] ?? '');
                $log['version']   = trim($line[4] ?? '');
                $log['os']        = trim($line[5] ?? '');
                $log['path']      = trim($line[6] ?? '');
                $log['message']   = trim($line[7] ?? '');
                $log['file']      = trim($line[8] ?? '');
                $log['backtrace'] = trim($line[9] ?? '');

                break;
            }

            fseek($this->fp, 0, SEEK_END);
            fclose($this->fp);
        }

        return $log;
    }

    /**
     * Create console log.
     *
     * @param string $message Log message
     * @param bool   $verbose  Is verbose
     * @param array  $context Context
     *
     * @return array
     */
    public function console(string $message, bool $verbose = true, array $context = [])
    {
        $message = date('[Y-m-d H:i:s] ') . $message . "\r\n";

        if ($verbose) {
            echo $message;
        } else {
            $this->info($message, $context);
        }
    }
}
