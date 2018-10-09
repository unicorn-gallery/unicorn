<?php

namespace lib;

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox as BaseDropbox;

class Dropbox
{
    /**
     * Fixed identifier for single-user system OR authenticated user ID
     */
    const CLIENT_ID = 1;

    /**
     * @var Dropbox
     */
    private static $instance;

    /**
     * @var BaseDropbox
     */
    public $api;

    private function __construct()
    {
        $this->api = new BaseDropbox(
            new DropboxApp(self::CLIENT_ID, Config::read('secret'), Config::read('access_token'))
        );
    }

    /**
     * @return Dropbox
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return self::$instance;
    }
}
