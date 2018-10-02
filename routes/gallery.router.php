<?php

use models\Gallery;
use lib\Config;
use lib\Cache;

$app->get('/', function () use ($app) {
  $gallery = new Gallery();
  $albums = $gallery->get_albums();
  $title = Config::read("gallery_name");
  $app->render("index.html", array("title" => $title, "entries" => $albums));
  $cache = new Cache();
  $cache->refresh();
});

$app->get('/:album', function($album) use ($app) {
  $gallery = new Gallery();
  $images = $gallery->get_album($album);
  $app->render("index.html", array("title" => $album, "entries" => $images, "lightbox" => True));
  $cache = new Cache();
  $cache->refresh();
});

?>
