<?php

$app->get('/admin', function () use ($app) {
    // Render index view
    $description = "When you click on the big button below, all gallery pictures will be fetched from Dropbox. "
      . "Do this every time you add pictures to your gallery. That's it.";

    $app->render('admin.html', array("gallery_name" => "Körts", "description" => $description));
});

$app->post('/admin', function () use ($app) {
    // Render index view
    $app->render('admin.html', array("gallery_name" => "Körts", "description" => "Has been refreshed"));
});

?>
