<?php

$config = array();

// You can set a name for your gallery which
// will be shown at the homepage.
$config["gallery_name"] = "unicorn gallery";

// Set your consumer key, secret and callback URL
$config["key"]    = 'szjinlzfr18qs2s';
$config["secret"] = 'e27a81z2qw3l0uj';

// Instantiate the Encrypter, passing it a 32-byte key
$config["encrypter_key"] = 'zOIJGOIJAÖVJKLXCVlksjflkjJSDF1J';

// To ensure we don't attempt to obtain an access token for each user request, we must store them.
// Tokens should be stored in a non-web-facing directory. Where?
$config["storage_object_dir"] = '/config';

// In order to improve page speed, all images get cached.
// We need a directory to put the images into.
$config["cache_dir"] = "/cache";

?>
