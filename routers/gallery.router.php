<?php

$app->get('/', function () use ($app) {
    // Render index view
    $app->render('index.html',array("gallery_name" => "Matthias"));
});

$app->get('/album/:name', function($name) {
  echo "Hello, $name";
});

?>
