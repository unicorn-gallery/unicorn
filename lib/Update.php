<?php

// Set error reporting
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

require '../vendor/autoload.php';
require_once("../config.php");

use lib\Cache;
use lib\Lock;
use lib\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logfile = Config::read('logfile');
$log = new Logger('applog');
$log->pushHandler(new StreamHandler($logfile, Logger::DEBUG));
$log->addInfo("Starting update");

$cache = new Cache();
$cache->refresh();

$log->addInfo("Finished update");

?>
