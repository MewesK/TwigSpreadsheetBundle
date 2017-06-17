<?php

namespace MewesK\TwigSpreadsheetBundle\Helper;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

/**
 * Class Filesystem.
 */
class Filesystem
{
    /**
     * @var BaseFilesystem
     */
    private static $delegate;

    /**
     * Creates a directory recursively.
     *
     * @param string|array|\Traversable $dirs The directory path
     * @param int                       $mode The directory mode
     *
     * @throws IOException On any directory creation failure
     */
    public static function mkdir($dirs, int $mode = 0777)
    {
        static::getDelegate()->mkdir($dirs, $mode);
    }

    /**
     * Checks the existence of files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to check
     *
     * @return bool true if the file exists, false otherwise
     */
    public static function exists($files): bool
    {
        return static::getDelegate()->exists($files);
    }

    /**
     * Removes files or directories.
     *
     * @param string|array|\Traversable $files A filename, an array of files, or a \Traversable instance to remove
     *
     * @throws IOException When removal fails
     */
    public static function remove($files)
    {
        static::getDelegate()->remove($files);
    }

    /**
     * Atomically dumps content into a file.
     *
     * @param string $filename The file to be written to
     * @param string $content  The data to write into the file
     *
     * @throws IOException If the file cannot be written to
     */
    public static function dumpFile(string $filename, string $content)
    {
        static::getDelegate()->dumpFile($filename, $content);
    }

    /**
     * @return BaseFilesystem
     */
    private static function getDelegate(): BaseFilesystem
    {
        if (!static::$delegate) {
            static::$delegate = new BaseFilesystem();
        }

        return static::$delegate;
    }
}
