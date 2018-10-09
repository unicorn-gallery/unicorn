<?php

namespace lib;

class Cache
{
    /**
     * @var Dropbox
     */
    private $dropbox;

    /**
     * @var string
     */
    private $cursorFile;

    /**
     * @var
     */
    private $api;

    /**
     * @var bool|null|string
     */
    private $cursor;

    public function __construct()
    {
        $this->dropbox = Dropbox::getInstance();
        $this->cursorFile = Config::read("storage_object_dir") . "/user.cursor";
        $this->cursor = $this->readCursor();
    }

    /**
     * Returns the current "state" of the cache
     * in form of a Dropbox cursor for the delta API
     * (see Dropbox API documentation for more info).
     */
    private function readCursor()
    {
        if (($content = File::read($this->cursorFile)) == '') {
            // An empty file means we have no cursor yet (no cache available).
            // In this case, the API expects a null object.
            return null;
        }

        return $content;
    }

    private function writeCursor($cursor)
    {
        File::write($this->cursorFile, $cursor);
    }

    /**
     * @return bool
     */
    public function isUpToDate()
    {
        if (!file_exists($this->cursorFile)) {
            return false;
        }

        $delta = date("U") - filemtime($this->cursorFile);

        return $delta <= Config::read("cache_update_after");
    }

    private function removeCursor()
    {
        File::remove($this->cursorFile);
    }

    public function clearCacheDir()
    {
        $cache_dir = Config::read("cache_dir");
        // Clear cache
        if (! isset($cache_dir)) {
            return;
        }
        Directory::rrmdir($cache_dir);
        mkdir($cache_dir);
    }

    public function clear()
    {
        $this->removeCursor();
        $this->clearCacheDir();
    }

    /**
     * Use the Dropbox delta API to cache the gallery.
     * Every image will be stored inside the cache directory
     * This increases loading speed significantly.
     *
     * @param bool $forceUpdate
     * @param bool $purge
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    public function refresh($forceUpdate = false, $purge = false)
    {
        // Do we need to check for updates?
        /*if ($this->isUpToDate() && !$forceUpdate) {
          return;
        }*/

        if ($purge) {
            $this->clear();
            $this->cursor = $this->readCursor();
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
                $this->writeEntry($entry);
            }
            // Refresh cursor
            $this->cursor = $changes->getCursor();

            // Get all changes until we're done
        } while ($changes->hasMoreItems());

        // Save current status
        $this->writeCursor($this->cursor);
    }

    /**
     * @param $entry
     *
     * @throws \Kunnu\Dropbox\Exceptions\DropboxClientException
     */
    private function writeEntry($entry)
    {
        if ($entry instanceof \Kunnu\Dropbox\Models\FolderMetadata) {
            // Don't write albums, only images.
            return;
        }
        $dirname = File::sanitize(dirname($entry->path_display));
        $basename = basename($entry->path_display);
        $localPath = Config::read("cache_dir") . "/" . $dirname . "/" . $basename;

        // Check if dir already exists. Create if not.
        Directory::rmkdir($localPath);

        $outfile = Dropbox::getInstance()->api->download($entry->path_display);
        File::write($localPath, $outfile->getContents());
        Image::createThumbnail($localPath);
    }
}

