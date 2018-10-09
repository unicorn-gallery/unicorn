<?php

require 'vendor/autoload.php';
require_once("config.php");

// Set error reporting
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => 'templates',
));

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('logs/app.log', \Psr\Log\LogLevel::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());


// Set application-wide route conditions for URL parameters
\Slim\Route::setDefaultConditions(array(
    // Album name is alphanumeric with underscores instead
    // of spaces.
    'album' => '[_a-zA-Z0-9]{1,}',
    // Image filenames consist of a name and an extension
    // separated by a dot.
    'image' => '[a-zA-Z0-9]+\.[a-zA-Z_]{3,4}'
));


// Automatically load router files
$routes = glob('routes/*.router.php');
foreach ($routes as $route) {
    require $route;
}

// Run app
$app->run();
