<?php

require 'vendor/autoload.php';
require_once("config.php");

use lib\Config;

// Set error reporting
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

// Prepare app
$template_dir = Config::read('template_dir');
$app = new \Slim\Slim(array(
  'templates.path' => $template_dir,
  'debug' => false // Don't prettyprint exceptions
));
// Show unformatted exceptions
$app->error(function ( Exception $e ) use ($app) {
    echo "error : " . $e;
});

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$logfile = Config::read('logfile');
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('applog');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(
      $logfile, \Psr\Log\LogLevel::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Automatically load router files
$routes = glob('routes/*.router.php');
foreach ($routes as $route) {
    require $route;
}

// Run app
$app->run();

?>

