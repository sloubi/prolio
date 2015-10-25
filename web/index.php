<?php

error_reporting(-1);
ini_set("display_errors", 1);

require '../vendor/autoload.php';

DEFINE('PUBLIC_DIR', __DIR__);

// Initialize Slim
$app = new \Slim\Slim();

// View
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("../Prolio/View");
$app->view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/../cache'
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Need install?
if (!file_exists('../config/config.php'))
{
    $app->get('/', '\Prolio\Controller\Install:index')->via('GET', 'POST')->name('install');
    $app->run();
    exit;
}

// Configuration
require '../config/config.php';
$app->config($config);

// Session
session_cache_limiter(false);
session_start();

// Debug
$debugbar = new \Slim\Middleware\DebugBar();
$app->add($debugbar);

// Database
$dbConfig = $app->config('database');
\Prolio\Model\Spdo::setMysqlParams($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);

// Site Title
$pageModel = new \Prolio\Model\Page();
$home = $pageModel->getBySlug('home');
$app->view->getInstance()->addGlobal('siteTitle', $home->name);

// Routes
require '../config/routes.php';

// Go
$app->run();
