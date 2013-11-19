<?php

namespace lib;

use lib\Dropbox;
use lib\File;
use lib\Config;
use lib\Lock;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Cache {

    private $dropbox;
    private $cursor_file;
    private $cursor;
    private $max_update_time;

    public function __construct() {
      $this->dropbox = Dropbox::get_instance();
      $this->cursor_file = Config::read("storage_object_dir") . "/user.cursor";
      $this->cursor = $this->read_cursor();
      $this->max_update_time = 300;

      $logfile = Config::read('logfile');
      $this->log = new Logger('applog');
      $this->log->pushHandler(new StreamHandler($logfile, Logger::DEBUG));
    }

    /**
     * Returns the current "state" of the cache
     * in form of a Dropbox cursor for the delta API
     * (see Dropbox API documentation for more info).
     */
    private function read_cursor() {
      $content = File::read($this->cursor_file);
      if ($content == "") {
        // An empty file means we have no cursor yet (no chache available).
        // In this case, the API expects a null object.
        return Null;
      }
      return $content;
    }

    private function write_cursor($cursor) {
      File::write($this->cursor_file, $cursor);
    }

    public function is_up_to_date() {
      $age = File::age($this->cursor_file);
      if ($age < 0) {
        // File does not exist
        return False;
      }
      $update_after = Config::read("cache_update_after");
      if ($age > $update_after) {
        return False;
      }
      return True;
    }

    private function remove_cursor() {
      File::remove($this->cursor_file);
    }

    public function clear_cache_dir() {
      $cache_dir = Config::read("cache_dir");
      // Clear cache
      if (!isset($cache_dir)) {
        return;
      }
      Directory::rrmdir($cache_dir);
      mkdir($cache_dir);
    }

    public function clear() {
      $this->remove_cursor();
      $this->clear_cache_dir();
    }

   /**
    * Use the Dropbox delta API to cache the gallery.
    * Every image will be stored inside the cache directory
    * This increases loading speed significantly.
    */
    public function refresh($force_update = False, $purge = False) {

      // Do we need to check for updates?
      if ($this->is_up_to_date() && !$force_update) {
        return False;
      }

      if (!Lock::get_lock("update")) {
        // Another process is updating the cache
        return False;
      }
      $this->log->addDebug("Refreshing cache");

      // Purge the cache?
      if ($purge) {
        $this->clear();
        $this->cursor = $this->read_cursor();
      }

      // Get changes
      $request = $this->dropbox->api->delta($this->cursor);
      $changes = $request["body"];

      // Did Dropbox tell us to reset the cache?
      // (Only clear cache if we did not purge it before.)
      if (!$purge && $changes->reset) {
        $this->clear();
      }

      // Write all changes until we're done
      do {
        $this->write_entries($changes);
        // Get changes
        if (!$changes->has_more) {
          break;
        }
        $request = $this->dropbox->api->delta($this->cursor);
        $changes = $request["body"];
      } while (True);

      Lock::release_lock("update");
      $this->log->addDebug("Finished refreshing cache");
      return True;
    }

    private function write_entries($changes) {
      $entries = $changes->entries;
      foreach ($entries as $entry) {
        $this->write_entry($entry);
      }
      // Refresh cursor
      $this->cursor = $changes->cursor;
      // Save current status
      // In case of an interruption we can
      // simply go from here.
      $this->write_cursor($this->cursor);
    }

    private function write_entry($entry) {
      if (sizeof($entry) < 1) {
        // Something's wrong with this entry. Skip.
        return;
      }
      $metadata = $entry[1];
      if ($metadata->is_dir) {
        // Don't write albums, only images.
        return;
      }
      $dirname = File::sanitize(dirname($metadata->path));
      $basename = basename($metadata->path);
      $basename = File::sanitize($basename);
      $local_path = Config::read("cache_dir") . "/" . $dirname . "/" . $basename;

      // Check if dir already exists. Create if not.
      Directory::rmkdir($local_path);

      $api = Dropbox::get_instance();
      $outfile = $this->dropbox->api->getFile($metadata->path);
      File::write($local_path, $outfile["data"]);
      Image::create_thumbnail($local_path);
    }
}

