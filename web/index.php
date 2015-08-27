<?php

error_reporting(-1);
ini_set("display_errors", 1);

require '../vendor/autoload.php';

// Initialize Slim
$app = new \Slim\Slim();

// Configuration
$app->config('mode', 'development');
require '../Prolio/config.php';

// View
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("../Prolio/View");
$app->view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/../cache'
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());


// Database
$dbConfig = $app->config('database');
\Prolio\Model\Spdo::setMysqlParams($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);

// Routes
require '../Prolio/routes.php';

// Go
$app->run();
