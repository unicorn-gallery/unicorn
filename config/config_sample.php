<?php

$config = array();

// Set your consumer key, secret and callback URL
$config["key"]    = 'XXXXXXXXXXXXXXX';
$config["secret"] = 'XXXXXXXXXXXXXXX';

// Instantiate the Encrypter, passing it a 32-byte key
$config["encrypter_key"] = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

// To ensure we don't attempt to obtain an access token for each user request, we must store them.
// Tokens should be stored in a non-web-facing directory. Where?
$config["storage_object_dir"] = '/outside/web/root/tokens';

// In order to improve page speed, all images get cached.
// We need a directory to put the images into.
$config["cache_dir"] = "cache";

?>
