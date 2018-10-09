<?php

namespace Mre\Unicorn\models;

use Mre\Unicorn\lib\Config;
use Mre\Unicorn\lib\Directory;
use Mre\Unicorn\lib\Dropbox;
use Mre\Unicorn\lib\File;

/**
 * Class Gallery
 */
class Gallery
{
    /**
     * @var string
     */
    private static $thumbs_dir = "thumbs";

    /**
     * @var Dropbox
     */
    protected $dropbox;

    /**
     * Gallery constructor.
     */
    public function __construct()
    {
        $this->dropbox = Dropbox::getInstance();
    }

    /**
     * @return bool|string
     */
    private function getCachePath()
    {
        return Directory::serverPath(Config::read("cache_dir"));
    }

    /**
     * Get public path to album cache
     *
     * @param string $album
     *
     * @return string
     */
    private function getAlbumPath($album)
    {
        return $this->getCachePath() . "/" . $album;
    }

    /**
     * Get album route url
     *
     * @param string $album
     *
     * @return string string
     */
    private function getAlbumUrl($album)
    {
        return Directory::pageUrl() . "/" . File::encode($album);
    }

    private function getImagePath($album, $image)
    {
        return $this->getAlbumPath($album) . "/" . $image;
    }

    /**
     * Return the public path to an image thumbnail
     * @param string $album
     * @param bool $img
     * @return bool|string
     */
    private function getThumbUrl($album, $img = false)
    {
        if (!$img) {
            // Get the absolute path to the thumbnail
            $dir = Config::read("cache_dir") . "/" . $album . "/" . self::$thumbs_dir;
            $thumbs = Directory::validEntries($dir);
            if (empty($thumbs)) {
                return false;
            }
            $img = $thumbs[0];
        }

        return $this->getAlbumPath($album) . "/" . self::$thumbs_dir . "/" . $img;
    }

    /**
     * Get an album
     *
     * @param string $albumName
     *
     * @return array
     */
    public function getAlbum($albumName)
    {
        $albumName = File::decode($albumName);
        $dir = Config::read("cache_dir") . "/" . $albumName;
        $entries = [];

        foreach (Directory::validEntries($dir, true) as $entry) {
            $name = File::decode(File::removeExtension($entry));
            $currentEntry = [
                "name"      => $name,
                "url"       => $this->getImagePath($albumName, $entry),
                "thumb_url" => $this->getThumbUrl($albumName, $entry)
            ];
            array_push($entries, $currentEntry);
        }

        return $entries;
    }

    /**
     * Get an array of album names and a thumbnail url for each album
     */
    public function getAlbums()
    {
        $entries = [];

        foreach (Directory::validEntries(Config::read("cache_dir")) as $entry) {
            if (!($thumbUrl = $this->getThumbUrl($entry))) {
                continue;
            }

            array_push($entries, [
                "name"      => File::decode($entry),
                "url"       => $this->getAlbumUrl($entry),
                "thumb_url" => $thumbUrl
            ]);
        }

        return $entries;
    }
}
