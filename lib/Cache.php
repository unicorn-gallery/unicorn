<?php

namespace lib;

use lib\Dropbox;
use lib\File;
use lib\Config;

class Cache {

    private $cursor_file;
    private $api;

    public function __construct() {
      $this->dropbox = Dropbox::get_instance();
      $this->cursor_file = Config::read("storage_object_dir") . "/user.cursor";
      $this->cursor = $this->read_cursor();
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
      if (!file_exists($this->cursor_file)) {
        return False;
      }

      $fmtime = filemtime($this->cursor_file);
      $currtime = date("U");
      $delta = $currtime - $fmtime;

      $update_after = Config::read("cache_update_after");
      if ($delta > $update_after) {
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
      /*if ($this->is_up_to_date() && !$force_update) {
        return;
      }*/

      if ($purge) {
        $this->clear();
        $this->cursor = $this->read_cursor();
      }

      // Get changes 
      if ($this->cursor) {
		  $changes = $this->dropbox->api->listFolderContinue($this->cursor);
	  } else {
		  $changes = $this->dropbox->api->listFolder('/', array("recursive" => true));
	  }

      do {
        $entries = $changes->getItems();
        foreach ($entries as $entry) {
          $this->write_entry($entry);
        }
        // Refresh cursor
        $this->cursor = $changes->getCursor();

      // Get all changes until we're done
      } while ($changes->hasMoreItems());

      // Save current status
      $this->write_cursor($this->cursor);
    }

  private function write_entry($entry) {
    if ($entry instanceof \Kunnu\Dropbox\Models\FolderMetadata) {
      // Don't write albums, only images.
      return;
    }
    $dirname = File::sanitize(dirname($entry->path_display));
  	$basename = basename($entry->path_display);
    $local_path = Config::read("cache_dir") . "/" . $dirname . "/" . $basename;

    // Check if dir already exists. Create if not.
    Directory::rmkdir($local_path);

    $api = Dropbox::get_instance();
    $outfile = $api->api->download($entry->path_display);
    File::write($local_path, $outfile->getContents());
    Image::create_thumbnail($local_path);
  }
}

