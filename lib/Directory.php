<?php

namespace lib;

// Directory functions
class Directory {
    /**
     * Recursively delete a directory and all of its contents.
     * See http://stackoverflow.com/a/1653776/270334
     */
    public static function rrmdir($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!rrmdir($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }
}

?>
