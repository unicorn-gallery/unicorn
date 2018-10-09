<?php

use models\Gallery;
use lib\Config;
use lib\Cache;

$app->get('/', function () use ($app) {
    $gallery = new Gallery();
    $albums = $gallery->getAlbums();
    $title = Config::read("gallery_name");
    $app->render("index.html", ["title" => $title, "entries" => $albums]);
    $cache = new Cache();
    $cache->refresh();
});

$app->get('/:album', function ($album) use ($app) {
    $gallery = new Gallery();
    $images = $gallery->getAlbum($album);
    $app->render("index.html", ["title" => $album, "entries" => $images, "lightbox" => true]);
    $cache = new Cache();
    $cache->refresh();
});
