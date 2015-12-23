<?php

// Get environment
// if host ends with ".local" then we are on the dev environment
$environment = (strpos($_SERVER['HTTP_HOST'], '.local') === strlen($_SERVER['HTTP_HOST']) - 6) ? 'dev' : 'prod';
if ($environment == 'dev')
{
    error_reporting(-1);
    ini_set("display_errors", 1);
}
else
{
    error_reporting(0);
    ini_set("display_errors", 0);
}


DEFINE('PUBLIC_DIR', __DIR__);
DEFINE('APP_DIR', PUBLIC_DIR . '/..');

require APP_DIR . '/vendor/autoload.php';

// Initialize Slim
$app = new \Slim\Slim();

// View
$app->view = new \Slim\Views\Twig();
$app->view->parserOptions = array(
    'debug' => true,
    'cache' => APP_DIR . '/cache'
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Need install?
if (!file_exists(APP_DIR . '/config/config.php'))
{
    $app->get('/', '\Prolio\Controller\Install:index')->via('GET', 'POST')->name('install');
    $app->run();
    exit;
}

// Configuration
require APP_DIR . '/config/config.php';
$app->config($config);
$app->view->setTemplatesDirectory(APP_DIR . '/themes/' . $config['site']['theme']);

// Session
session_cache_limiter(false);
session_start();

// Debug
if ($environment == 'dev')
{
    $debugbar = new \Slim\Middleware\DebugBar();
    $app->add($debugbar);
}

// Database
$dbConfig = $app->config('database');
\Prolio\Model\Spdo::setMysqlParams($dbConfig['host'], $dbConfig['name'], $dbConfig['user'], $dbConfig['pass']);

// Site Title
$pageModel = new \Prolio\Model\Page();
$home = $pageModel->getBySlug('home');
$app->view->getInstance()->addGlobal('siteTitle', $home->name);

// Routes
require APP_DIR . '/config/routes.php';

// Go
$app->run();
