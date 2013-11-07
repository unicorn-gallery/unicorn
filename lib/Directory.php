<?php

namespace lib;
use lib\Config;

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
            if (!self::rrmdir($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
    }

    /**
     * Create the given directory hierarchy
     */
    public static function rmkdir($path) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
          return mkdir($dir, 0777, True);
        }
        return True;
    }

  public static function server_path($path) {
    $root = Config::read("root_path");
    if (substr($path, 0, strlen($root)) == $root) {
        $path = substr($path, strlen($root));
    }
    return $path;
  }
}

?>
