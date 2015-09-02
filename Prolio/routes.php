<?php

$app->get('/', function () use ($app) {
    $app->render('index.twig');
});

$app->get('/project/:id', '\Prolio\Controller\Project:get')->name('project');
$app->get('/projects', '\Prolio\Controller\Project:all')->name('projects');

$app->get('/contact', '\Prolio\Controller\Contact:get')->name('contact');
$app->post('/contact', '\Prolio\Controller\Contact:post');


// Route Middleware: admin is logged ?
function checkAdminAccess()
{
    $user = new \Prolio\Model\User();
    if (!$user->isLogged())
    {
        $app = \Slim\Slim::getInstance();
        $app->redirect('/admin/login');
    }
}

$app->group('/admin', function () use ($app) {
    $app->get('/login', '\Prolio\Controller\Backend:loginGet')->name('login');
    $app->post('/login', '\Prolio\Controller\Backend:loginPost');

    $app->get('/home', 'checkAdminAccess', '\Prolio\Controller\Backend:home');
    $app->get('/logout', 'checkAdminAccess', '\Prolio\Controller\Backend:logout');
});