<?php

use models\Gallery;
use lib\Config;

$app->get('/admin', function () use ($app) {
    $app->render('admin.html', array("gallery_name" => Config::read("gallery_name")));
});

$app->post('/admin', function () use ($app) {
    $gallery = new Gallery();
    $gallery->refresh_cache();
    $app->render('admin.html', array("gallery_name" => Config::read("gallery_name"), "refreshed" => True));
});

?>
