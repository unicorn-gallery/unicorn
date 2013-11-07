<?php

namespace lib;

use lib\Dropbox;
use lib\File;
use lib\Config;

class Cache {

    private static $cursor_file = Config::read("storage_object_dir") . "/user.cursor";
    private static $cursor;
    private $api;

    /**
     * Returns the current "state" of the cache
     * in form of a Dropbox cursor for the delta API
     * (see Dropbox API documentation for more info).
     */
    private static function read_cursor() {
      $content = File::read(self::$cursor_file);
      if ($content == "") {
        // An empty file means we have no cursor yet (no chache available).
        // In this case, the API expects a null object.
        return Null;
      }
      return $content;
    }

    private static function write_cursor($cursor) {
      File::write(self::$cursor_file, $cursor);
    }

    public static function is_up_to_date() {
      // TODO: Check timestamp
      return False;
    }

    public static function clear_cache() {
      $cache_dir = Config::read("cache_dir");
      // Clear cache
      if (!isset($cache_dir)) {
        return;
      }
      Directory::rrmdir($cache_dir);
      mkdir($cache_dir);
    }

   /**
    * Use the Dropbox delta API to cache the gallery.
    * Every image will be stored inside the cache directory
    * This increases loading speed significantly.
    */
    public static function refresh() {

      // Do we need to check for updates?
      if (self::is_up_to_date()) {
        return;
      }

      // Get changes
      $api = Dropbox::get_instance();
      $request = $api->delta($this->cursor);
      $changes = $request["body"];

      // Did we receive a command to purge the cache?
      if ( $changes->reset ) {
        //$this->clear_cache();
      }

      do {
        $entries = $changes->entries;
        foreach ($entries as $entry) {
          self::write_entry($entry);
        }
        // Refresh cursor
        $this->cursor = $changes->cursor;

      // Get all changes until we're done
      } while ($changes->has_more);

      // Save current status
      $this->write_cursor($this->cursor);
    }

  private static function write_entry($entry) {
    $metadata = $entry[1];
    if ($metadata->is_dir) {
      // Don't write albums, only images.
      return;
    }

    $local_path = Config::read("cache_dir") . $metadata->path;
    // Check if dir already exists. Create if not.
    Directory::rmkdir($local_path);

    $api = Dropbox::get_instance();
    $this->api->getFile($metadata->path, $local_path);
    Image::create_thumbnail($local_path);
  }

  /**
   * Use the Dropbox API to cache the gallery.
   * Every image will be stored inside the cache directory
   * This increases loading speed significantly.
   */
  public static function refresh_old() {

    $this->clear_cache();

    // Get the metadata for the gallery folder
    $metaData = $this->dropbox->metaData('/');

    // List gallery contents
    foreach ($metaData["body"]->contents as $metaPath) {

      // Get albums
      if ($metaPath->is_dir) {

        // TODO: Check if album is already in cache (delta api)
        // echo strtotime($metaPath->modified);

        // Create a directory for every album
        $cache_album_dir = Config::read("cache_dir") . $metaPath->path;
        mkdir($cache_album_dir);

        // Get the metadata for gallery albums
        $metaAlbum = $this->dropbox->metaData($metaPath->path);

        // Get pictures in album
        foreach ($metaAlbum["body"]->contents as $key=>$metaPic) {

          // Store every picture with its thumbnail inside a cache
          if ($metaPic->thumb_exists) {
            $pic = $metaPic->path;

            // Store thumbnail provided by Dropbox
            //$thumb = $dropbox->thumbnails($pic, "JPEG", "m");
            //file_put_contents("test_thumb.jpg", $thumb["data"]);

            $cache_picture_name = $cache_album_dir . "/" . $key . ".jpg";
            $this->dropbox->getFile($pic, $cache_picture_name);
            Image::create_thumbnail($cache_picture_name);
          }
        }
      }
    }
  }

}

