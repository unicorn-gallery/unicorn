<?php

use models\Gallery;
use lib\Config;
use lib\Cache;

$app->get('/admin', function () use ($app) {
    $app->render('admin.html', array("gallery_name" => Config::read("gallery_name")));
});

$app->post('/admin', function () use ($app) {
    $cache = new Cache();
    $cache->refresh(True, True);
    $app->render('admin.html', array("gallery_name" => Config::read("gallery_name"), "refreshed" => True));
});

?>
