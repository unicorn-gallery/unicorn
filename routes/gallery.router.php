<?php

use models\Gallery;
use lib\Config;
use lib\Directory;
use Bc\BackgroundProcess\BackgroundProcess;


$app->get('/', function () use ($app) {
  $gallery = new Gallery();
  $albums = $gallery->get_albums();
  $title = Config::read("gallery_name");
  $asset_dir_absolute = Config::read("asset_dir");
  $asset_dir = Directory::path_from_server_root($asset_dir_absolute);
  $app->render("index.html", array( "title" => $title,
                                    "entries" => $albums,
                                    "assets" => $asset_dir));
  $update = new BackgroundProcess('php lib/Update.php');
  $update->run();
});

$app->get('/:album', function($album) use ($app) {
  $gallery = new Gallery();
  $images = $gallery->get_album($album);
  $asset_dir_absolute = Config::read("asset_dir");
  $asset_dir = Directory::path_from_server_root($asset_dir_absolute);
  $app->render("index.html", array( "title" => $album,
                                    "entries" => $images,
                                    "lightbox" => True,
                                    "assets" => $asset_dir));
  $update = new BackgroundProcess('php lib/Update.php');
  $update->run();
});

?>
