<?php

namespace models;

use lib\Directory;
use lib\File;
use lib\Dropbox;
use lib\Image;
use lib\Config;

class Gallery {

	protected $dropbox;
  private static $thumbs_dir = "thumbs";

	function __construct() {
    $this->dropbox = \lib\Dropbox::get_instance();
	}


  public function refresh_cache() {
    $this->dropbox->refresh_cache();
  }

  private function valid_entries($dir, $exclude_dirs = False, $filters = array(".", "..")) {
    if (!is_dir($dir)) return array();
    $entries = array();
    foreach (scandir($dir) as $entry) {
      if ($exclude_dirs && is_dir($dir . "/" . $entry)) continue;
      if (in_array($entry, $filters)) continue;
      array_push($entries, $entry);
    }
    return $entries;
  }

  private function get_cache_path() {
    $cache_url = Config::read("cache_dir");
    return Directory::server_path($cache_url);
  }

  private function get_album_url($album) {
    $cache_url = $this->get_cache_path();
    return $cache_url . "/" . $album;
  }

  private function get_image_url($album, $image) {
    $album_url = $this->get_album_url($album);
    return $album_url . "/" . $image;
  }

  /**
   * Return the public path to an image thumbnail
   */
  private function get_thumb_url($album, $img = False) {
    $album_url = $this->get_album_url($album);
    $thumbs_url = $album_url . "/" . self::$thumbs_dir;
    if (!$img) {
      // Get the absolute path to the thumbnail
      $dir = Config::read("cache_dir") . "/" . $album . "/" . self::$thumbs_dir;
      $thumbs = $this->valid_entries($dir);
      if (empty($thumbs)) return False;
      $img = $thumbs[0];
    }
    return $thumbs_url . "/" . $img;
  }

  /**
   * Get an album
   */
  public function get_album($album_name) {
    $dir = Config::read("cache_dir") . "/" . $album_name;
    $entries = array();
    foreach ($this->valid_entries($dir, True) as $entry) {
      $curr_entry = array("name" => File::remove_extension($entry),
                          "url" => $this->get_image_url($album_name, $entry),
                          "thumb_url" => $this->get_thumb_url($album_name, $entry));
      array_push($entries, $curr_entry);
    }
    return $entries;
  }

  /**
   * Get an array of album names and a thumbnail url for each album
   */
  public function get_albums() {
    $dir = Config::read("cache_dir");
    $entries = array();
    foreach ($this->valid_entries($dir) as $entry) {
      $thumb_url = $this->get_thumb_url($entry);
      if (!$thumb_url) continue;

      $curr_entry = array("name" => $entry,
                          "url" => $this->get_album_url($entry),
                          "thumb_url" => $thumb_url);
      array_push($entries, $curr_entry);
    }
    return $entries;
  }
}
?>
