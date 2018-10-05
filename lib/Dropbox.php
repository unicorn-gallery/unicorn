<?php

namespace lib;

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox as BaseDropbox;

class Dropbox
{
    private static $instance;
    public $api;

    private function __construct()
    {
        $identifier = 1; // Fixed identifier for single-user system OR authenticated user ID

        $this->api = new BaseDropbox(
            new DropboxApp($identifier, Config::read('secret'), Config::read('access_token'))
        );
    }

    /**
     * @return static
     */
    public static function get_instance()
    {
        if (! static::$instance) {
            static::$instance = new static();
        }

        return self::$instance;
    }
}
