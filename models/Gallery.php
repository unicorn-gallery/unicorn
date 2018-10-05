<?php

namespace models;

use lib\Directory;
use lib\Dropbox;
use lib\File;
use lib\Config;

class Gallery
{
    private static $thumbs_dir = "thumbs";
    protected $dropbox;

    function __construct()
    {
        $this->dropbox = Dropbox::get_instance();
    }

    private function get_cache_path()
    {
        return Directory::server_path(Config::read("cache_dir"));
    }

    /**
     * Get public path to album cache
     *
     * @param string $album
     *
     * @return string
     */
    private function get_album_path($album)
    {
        return $this->get_cache_path() . "/" . $album;
    }

    /**
     * Get album route url
     *
     * @param string $album
     *
     * @return string string
     */
    private function get_album_url($album)
    {
        return Directory::page_url() . "/" . File::encode($album);
    }

    private function get_image_path($album, $image)
    {
        return $this->get_album_path($album) . "/" . $image;
    }

    /**
     * Return the public path to an image thumbnail
     */
    private function get_thumb_url($album, $img = false)
    {
        if (! $img) {
            // Get the absolute path to the thumbnail
            $dir = Config::read("cache_dir") . "/" . $album . "/" . self::$thumbs_dir;
            $thumbs = Directory::valid_entries($dir);
            if (empty($thumbs)) {
                return false;
            }
            $img = $thumbs[0];
        }

        return $this->get_album_path($album) . "/" . self::$thumbs_dir . "/" . $img;
    }

    /**
     * Get an album
     *
     * @param string $album_name
     *
     * @return array
     */
    public function get_album($album_name)
    {
        $album_name = File::decode($album_name);
        $dir = Config::read("cache_dir") . "/" . $album_name;
        $entries = array();

        foreach (Directory::valid_entries($dir, true) as $entry) {
            $name = File::decode(File::remove_extension($entry));
            $curr_entry = array(
                "name"      => $name,
                "url"       => $this->get_image_path($album_name, $entry),
                "thumb_url" => $this->get_thumb_url($album_name, $entry)
            );
            array_push($entries, $curr_entry);
        }

        return $entries;
    }

    /**
     * Get an array of album names and a thumbnail url for each album
     */
    public function get_albums()
    {
        $entries = array();

        foreach (Directory::valid_entries(Config::read("cache_dir")) as $entry) {
            if (! ($thumb_url = $this->get_thumb_url($entry))) {
                continue;
            }

            array_push($entries, array(
                "name"      => File::decode($entry),
                "url"       => $this->get_album_url($entry),
                "thumb_url" => $thumb_url
            ));
        }

        return $entries;
    }
}
