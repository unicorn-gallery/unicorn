<?php

use models\Gallery;
use lib\Config;

$app->get('/', function () use ($app) {
    // Render index view
    $gallery = new Gallery();
    $albums = $gallery->get_albums();
    $app->render("index.html", array("gallery_name" => Config::read("gallery_name"), "albums" => $albums));
});


$app->get('/auth', function () use ($app) {
    // Read OAuth credentials
    $token = $app->request->get('oauth_token');
    echo $token;
});

$app->get('/album/:name', function($name) {
  echo "Hello, $name";
});

?>
