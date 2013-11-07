<?php

namespace lib;

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

      // Instantiate the Encrypter and storage objects
      $encrypter = new \Dropbox\OAuth\Storage\Encrypter(Config::read("encrypter_key"));

      // Instantiate the storage object, passing it the Encrypter and identifier
      $identifier = 1; // Fixed identifier for single-user system OR authenticated user ID
      $storage = new \Dropbox\OAuth\Storage\Filesystem($encrypter, $identifier);
      $storage->setDirectory(Config::read("storage_object_dir"));

      // As an alternative to file system storage of the tokens, you can also
      // use the PDO database layer.
      // User ID assigned by your auth system (used by persistent storage handlers)
      // $userID = 1;
      // Instantiate the database data store and connect
      // $storage = new \Dropbox\OAuth\Storage\PDO($encrypter, $userID);
      // $storage->connect('localhost', 'dropbox', 'dropbox', 'xxxxxxxxxx', 3306);

      // Create the consumer and API objects
      $OAuth = new \Dropbox\OAuth\Consumer\Curl(Config::read("key"), Config::read("secret"), $storage, $callback);

      $this->api = new \Dropbox\API($OAuth);

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

