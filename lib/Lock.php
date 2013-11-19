<?php

namespace lib;

use lib\File;
use lib\Config;

class Lock {

    private static $lock_extension = ".lock";

    // Force release after a certain time
    private static $max_lock_time = 300;

    private static function lock_path($name) {
      return Config::read("storage_object_dir") . "/" . $name . self::$lock_extension;
    }

    public static function get_lock($name) {
      $lock = self::lock_path($name);
      $age = File::age($lock);
      if ($age < 0) {
        // No lock set, get lock
        File::write($lock, "running");
        return True;
      }
      // Check if it is set for too long
      if ($age > self::$max_lock_time) {
        // Jup, steal the lock
        File::remove($lock);
        File::write($lock, "running");
        return True;
      }
      // Update in progress
      return False;
    }

    public static function release_lock($name) {
      $lock = self::lock_path($name);
      File::remove($lock);
    }
}
?>
