<?php

use Mre\Unicorn\lib\Cache;
use Mre\Unicorn\lib\Config;

$app->get('/admin', function () use ($app) {
    $app->render('admin.html', ["gallery_name" => Config::read("gallery_name")]);
});

$app->post('/admin', function () use ($app) {
    $cache = new Cache();
    $cache->refresh(true, true);
    $app->render('admin.html', ["gallery_name" => Config::read("gallery_name"), "refreshed" => true]);
});
