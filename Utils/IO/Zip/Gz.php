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

namespace phpOMS\Utils\IO\Zip;

/**
 * Zip class for handling zip files.
 *
 * Providing basic zip support
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class Gz implements ArchiveInterface
{
    /**
     * {@inheritdoc}
     */
    public static function pack($source, string $destination, bool $overwrite = true) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));
        if (!$overwrite && file_exists($destination)) {
            return false;
        }

        if (($gz = gzopen($destination, 'w')) === false) {
            return false;
        }

        $src = fopen($source, 'r');
        while (!feof($src)) {
            gzwrite($gz, fread($src, 4096));
        }

        fclose($src);

        return gzclose($gz);
    }

    /**
     * {@inheritdoc}
     */
    public static function unpack(string $source, string $destination) : bool
    {
        $destination = str_replace('\\', '/', realpath($destination));
        if (file_exists($destination)) {
            return false;
        }

        if (($gz = gzopen($source, 'w')) === false) {
            return false;
        }

        $dest = fopen($destination, 'w');
        while (!gzeof($gz)) {
            fwrite($dest, gzread($gz, 4096));
        }

        fclose($dest);

        return gzclose($gz);
    }
}
