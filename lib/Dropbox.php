<?php

namespace lib;

use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\Dropbox as BaseDropbox;

use lib\Directory;
use lib\File;
use lib\Config;

class Dropbox {

    private static $instance;
    private $cursor_file;
    private $cursor;
    public $api;

    private function __construct() {
      // Check whether to use HTTPS and set the callback URL
      $protocol = (!empty($_SERVER['HTTPS'])) ? 'https' : 'http';
      $callback = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

      $identifier = 1; // Fixed identifier for single-user system OR authenticated user ID

      $app = new DropboxApp($identifier, Config::read("secret"), Config::read("access_token"));
      $dropbox = new BaseDropbox($app);

      $this->api = $dropbox;

    }

    public static function get_instance() {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }
}

