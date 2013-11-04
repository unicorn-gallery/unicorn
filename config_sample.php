<?php

use lib\Config;

// You can set a name for your gallery which
// will be shown at the homepage.
Config::write("gallery_name", "unicorn gallery");

// Set your consumer key, secret and callback URL
Config::write("key", "xxxxxxxxxxxxxxx");
Config::write("secret", "xxxxxxxxxxxxxxx");

// Instantiate the Encrypter, passing it a 32-byte key
Config::write("encrypter_key", "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx");

// To ensure we don"t attempt to obtain an access token for each user request, we must store them.
// Tokens should be stored in a non-web-facing directory. Where?
Config::write("storage_object_dir", "/config");

// In order to improve page speed, all images get cached.
// We need a directory to put the images into.
Config::write("cache_dir", "/cache");

?>
