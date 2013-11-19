<?php

use models\Gallery;
use lib\Config;
use lib\Cache;
use lib\Directory;


$app->get('/admin', function () use ($app) {
    $asset_dir_absolute = Config::read("asset_dir");
    $asset_dir = Directory::path_from_server_root($asset_dir_absolute);
    $app->render('admin.html', array( "gallery_name" => Config::read("gallery_name"),
                                      "assets" => $asset_dir));
});

$app->post('/admin', function () use ($app) {
    $asset_dir_absolute = Config::read("asset_dir");
    $asset_dir = Directory::path_from_server_root($asset_dir_absolute);
    $cache = new Cache();
    $cache->refresh(True, True);
    $app->render('admin.html', array( "gallery_name" => Config::read("gallery_name"),
                                      "refreshed" => True,
                                      "assets" => $asset_dir));
});

?>
