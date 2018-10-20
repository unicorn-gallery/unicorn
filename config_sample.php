<?php

use Mre\Unicorn\lib\Config;

// You can set a name for your gallery which
// will be shown at the homepage.
Config::write("gallery_name", "unicorn gallery");

// Set your consumer key, secret and callback URL
Config::write("key", getenv('UNICORN_KEY') ? getenv('UNICORN_KEY') : "xxxxxxxxxxxxxxx");
Config::write("secret", getenv('UNICORN_SECRET') ? getenv('UNICORN_SECRET') : "xxxxxxxxxxxxxxx");
Config::write("access_token", getenv('UNICORN_ACCESS_TOKEN') ? getenv('UNICORN_ACCESS_TOKEN') : "xxxxxxxxxxxxxxx");

// To ensure we don't attempt to obtain an access token for each user request, we must store it
// inside a directory.
// We also store the cursor for the delta api inside this directory.
// Tokens should be stored in a non-web-facing directory. Where?
// (Give an absolute path!)
Config::write("storage_object_dir", __DIR__ . "/data");

// In order to improve page speed, all images get cached.
// We need a directory to put the images into.
// Most likely you don't need to change this setting.
// This directory must be publicly accessible.
Config::write("cache_dir", __DIR__ . "/cache");

// You can also specify the update frequency of the cache.
// By default we refresh the cache after five minutes (300s).
Config::write("cache_update_after", 300);

// Template, asset and logfile directories.
// Only change if you know what you are doing.
Config::write("template_dir", __DIR__ . "/templates");
Config::write("asset_dir", __DIR__ . "/assets");
Config::write("logfile", __DIR__ . "/logs/logfile.txt");

// Constant with absolute path to the public-facing root of the server.
// Don't change.
Config::write("root_path", $_SERVER['DOCUMENT_ROOT']);
