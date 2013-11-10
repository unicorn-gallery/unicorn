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

    /**
     * Get the public root of the server
     */
    public static function server_path($path) {
      $root = Config::read("root_path");
      if (substr($path, 0, strlen($root)) == $root) {
          $path = substr($path, strlen($root));
      }
      return $path;
    }

    public static function valid_entries($dir, $exclude_dirs = False, $filters = array(".", "..")) {
      if (!is_dir($dir)) return array();
      $entries = array();
      foreach (scandir($dir) as $entry) {
        if ($exclude_dirs && is_dir($dir . "/" . $entry)) continue;
        if (in_array($entry, $filters)) continue;
        array_push($entries, $entry);
      }
      return $entries;
    }
}

?>
