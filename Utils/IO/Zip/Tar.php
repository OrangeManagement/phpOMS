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
namespace phpOMS\Utils\IO\Zip;
/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Tar implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack(array $source, string $destination, bool $overwrite = true) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));

        if (!$overwrite && file_exists($destination)) {
            return false;
        }

        foreach ($sources as $source) {
            $source = str_replace('\\', '/', realpath($source));

            if (!file_exists($source)) {
                continue;
            }

            if (is_dir($source)) {
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

                foreach ($files as $file) {
                    $file = str_replace('\\', '/', $file);

                    /* Ignore . and .. */
                    if (in_array(mb_substr($file, mb_strrpos($file, '/') + 1), ['.', '..'])) {
                        continue;
                    }

                    $file = realpath($file);

                    if (is_dir($file)) {
                        // todo: do work here
                    } elseif (is_file($file)) {
                        // todo: do work here
                    }
                }
            } elseif (is_file($source)) {
                // todo: do work here
            }
        }

        fwrite($tar, pack('a1024', ''));
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        
    }
}