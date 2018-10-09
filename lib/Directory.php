<?php

namespace lib;

/**
 * Directory functions
 */
class Directory
{
    /**
     * Recursively delete a directory and all of its contents.
     * See http://stackoverflow.com/a/1653776/270334
     *
     * @param string $dir
     * @return bool
     */
    public static function rrmdir($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::rrmdir($dir.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }
        return rmdir($dir);
    }

    /**
     * Create the given directory hierarchy
     * @param string $path
     * @return bool
     */
    public static function rmkdir($path)
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            return mkdir($dir, 0777, true);
        }
        return true;
    }

    /**
     * Get the public root of the server
     * @param string $path
     * @return bool|string
     */
    public static function serverPath($path)
    {
        $root = Config::read("root_path");
        if (substr($path, 0, strlen($root)) == $root) {
            $path = substr($path, strlen($root));
        }
        return $path;
    }

    /**
     * @return bool|string
     */
    public static function pageUrl()
    {
        return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
    }

    /**
     * @param string $dir
     * @param bool $excludeDirs
     * @param array $filters
     * @return array
     */
    public static function validEntries($dir, $excludeDirs = false, $filters = [".", ".."])
    {
        if (!is_dir($dir)) {
            return [];
        }

        $entries = [];
        foreach (scandir($dir) as $entry) {
            if ($excludeDirs && is_dir($dir . "/" . $entry)) {
                continue;
            }
            if (in_array($entry, $filters)) {
                continue;
            }
            array_push($entries, $entry);
        }
        return $entries;
    }
}
