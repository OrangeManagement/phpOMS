<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Utils\TaskSchedule;

/**
 * Task scheduler class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 * @codeCoverageIgnore
 */
class TaskScheduler extends SchedulerAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
        unset($lines[0]);

        $jobs = [];
        foreach ($lines as $line) {
            $jobs[] = Schedule::createWith(\str_getcsv($line));
        }

        return $jobs;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllByName(string $name, bool $exact = true) : array
    {
        if ($exact) {
            $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV /tn ' . \escapeshellarg($name))));
            unset($lines[0]);

            $jobs = [];
            foreach ($lines as $line) {
                $jobs[] = Schedule::createWith(\str_getcsv($line));
            }
        } else {
            $lines = \explode("\n", $this->normalize($this->run('/query /v /fo CSV')));
            unset($lines[0]);

            $jobs = [];
            foreach ($lines as $key => $line) {
                $line = \str_getcsv($line);

                if (\stripos($line[1], $name) !== false) {
                    $jobs[] = Schedule::createWith($line);
                }
            }
        }

        return $jobs;
    }
}
