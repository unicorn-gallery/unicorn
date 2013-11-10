<?php

namespace models;

use lib\Directory;
use lib\File;
use lib\Server;
use lib\Dropbox;
use lib\Image;
use lib\Config;

class Gallery {

	protected $dropbox;
  private static $thumbs_dir = "thumbs";

	function __construct() {
    $this->dropbox = \lib\Dropbox::get_instance();
	}

  private function get_cache_path() {
    $cache_url = Config::read("cache_dir");
    return Directory::server_path($cache_url);
  }

  /**
   * Get public path to album cache
   */
  private function get_album_path($album) {
    $cache_url = $this->get_cache_path();
    return $cache_url . "/" . $album;
  }

  /**
   * Get album route url
   */
  private function get_album_url($album) {
    return Server::page_url() . "/" . File::encode($album);
  }

  private function get_image_path($album, $image) {
    $album_path = $this->get_album_path($album);
    return $album_path . "/" . $image;
  }

  /**
   * Return the public path to an image thumbnail
   */
  private function get_thumb_url($album, $img = False) {
    $album_url = $this->get_album_path($album);
    $thumbs_url = $album_url . "/" . self::$thumbs_dir;
    if (!$img) {
      // Get the absolute path to the thumbnail
      $dir = Config::read("cache_dir") . "/" . $album . "/" . self::$thumbs_dir;
      $thumbs = Directory::valid_entries($dir);
      if (empty($thumbs)) return False;
      $img = $thumbs[0];
    }
    return $thumbs_url . "/" . $img;
  }

  /**
   * Get an album
   */
  public function get_album($album_name) {
    $album_name = File::decode($album_name);
    $dir = Config::read("cache_dir") . "/" . $album_name;
    $entries = array();
    foreach (Directory::valid_entries($dir, True) as $entry) {
      $name = File::decode(File::remove_extension($entry));
      $curr_entry = array("name" => $name,
                          "url" => $this->get_image_path($album_name, $entry),
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
    foreach (Directory::valid_entries($dir) as $entry) {
      $thumb_url = $this->get_thumb_url($entry);
      if (!$thumb_url) continue;

      $name = File::decode($entry);
      $curr_entry = array("name" => $name,
                          "url" => $this->get_album_url($entry),
                          "thumb_url" => $thumb_url);
      array_push($entries, $curr_entry);
    }
    return $entries;
  }
}
?>
