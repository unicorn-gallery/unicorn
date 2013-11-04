<?php

namespace models;

use lib\Directory;
use lib\Dropbox;

class Gallery {

	protected $dropbox;
  private $config;

	function __construct($config) {
    $this->config = $config;
    $this->dropbox = \lib\Dropbox::getInstance();
	}

  private function clear_cache() {
    // Clear cache
    if (!isset($this->config["cache_dir"])) {
      return;
    }
    Directory::rrmdir($this->config["cache_dir"]);
    mkdir($this->config["cache_dir"]);
  }

  /**
   * Use the Dropbox API to cache the gallery.
   * Every image will be stored inside the cache directory
   * This increases loading speed significantly.
   */
  public function refresh_cache() {

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
        $cache_album_dir = $this->config["cache_dir"] . $metaPath->path;
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
            create_thumbnail($cache_picture_name);
          } // End store picture
        } // End foreach gallery
      } // End get albums
    } // End list gallery contents
  }

  private function valid_entries($dir, $filters = array(".", "..")) {
    if (!is_dir($dir)) return array();
    $entries = array();
    foreach (scandir($dir) as $entry) {
      if (in_array($entry, $filters)) continue;
      array_push($entries, $entry);
    }
    return $entries;
  }

  /**
   * Get an array of album names and a thumbnail url for each album
   */
  public function get_albums() {
    $cache_dir = $this->config["cache_dir"];
    $albums = array();
    foreach ($this->valid_entries($cache_dir) as $album) {
      $thumbs_path = $cache_dir . "/" . $album . "/thumbs";
      $entries = $this->valid_entries($thumbs_path);

      if (empty($entries)) continue;
      $curr_album = array("name" => $album,
                          "url" => str_replace(' ', '_', $album),
                          "thumb_url" => $thumbs_path . "/" . $entries[0]);
      array_push($albums, $curr_album);
    }
    return $albums;
  }
}
