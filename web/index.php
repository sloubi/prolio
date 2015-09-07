<?php

error_reporting(-1);
ini_set("display_errors", 1);

require '../vendor/autoload.php';

// Initialize Slim
$app = new \Slim\Slim();

// Configuration
DEFINE('PUBLIC_DIR', dirname(__FILE__));
$config = false;
if (file_exists('../Prolio/config.php'))
{
    require '../Prolio/config.php';
    $app->config($config);
}

// Session
session_cache_limiter(false);
session_start();

// Debug
$debugbar = new \Slim\Middleware\DebugBar();
$app->add($debugbar);

// Database
if ($config)
{
    $dbConfig = $app->config('database');
    \Prolio\Model\Spdo::setMysqlParams($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);
}

// View
$app->view = new \Slim\Views\Twig();
$app->view->setTemplatesDirectory("../Prolio/View");
$app->view->parserOptions = array(
    'debug' => true,
    'cache' => dirname(__FILE__) . '/../cache'
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Title
if ($config)
{
    $pageModel = new \Prolio\Model\Page();
    $home = $pageModel->getBySlug('home');
    $app->view->getInstance()->addGlobal('siteTitle', $home->name);
}

// Routes
if ($config)
{
    require '../Prolio/routes.php';
}
else
{
    $app->get('/', '\Prolio\Controller\Install:index')->via('GET', 'POST')->name('install');
}

// Go
$app->run();
