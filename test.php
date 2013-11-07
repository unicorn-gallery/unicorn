<?php

require 'vendor/autoload.php';
require_once("config.php");

use lib\Config;

$cache = Config::read("cache_dir");

function server_path($path) {
  $root = Config::read("root_path");
  if (substr($path, 0, strlen($root)) == $root) {
      $path = substr($path, strlen($root));
  }
  return $path;
}

echo $cache . "\n";
echo server_path($cache);
?>
