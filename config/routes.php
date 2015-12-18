<?php

$app->get('/', '\Prolio\Controller\Home:index')->name('home');

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
        $app->redirect($app->urlFor('login'), 401);
    }
}

$app->group('/' . $config['user']['adminLink'], function () use ($app) {
    $app->get('/login', '\Prolio\Controller\Backend:loginGet')->name('login');
    $app->post('/login', '\Prolio\Controller\Backend:loginPost');
    $app->get('/logout', 'checkAdminAccess', '\Prolio\Controller\Backend:logout')->name('logout');

    $app->map('/', 'checkAdminAccess', '\Prolio\Controller\Backend:index')->via('GET', 'POST')->name('admin');

    $app->get('/project/list', 'checkAdminAccess', '\Prolio\Controller\ProjectBackend:all')->name('project_list');
    $app->get('/project/add', 'checkAdminAccess', '\Prolio\Controller\ProjectBackend:add')->name('project_add');
    $app->get('/project/edit/:id', 'checkAdminAccess', '\Prolio\Controller\ProjectBackend:edit')->name('project_edit');
    $app->post('/project/process/(:id)', 'checkAdminAccess', '\Prolio\Controller\ProjectBackend:process')->name('project_process');
    $app->get('/project/delete/:id', 'checkAdminAccess', '\Prolio\Controller\ProjectBackend:delete')->name('project_delete');

    $app->get('/page/list', 'checkAdminAccess', '\Prolio\Controller\PageBackend:all')->name('page_list');
    $app->get('/page/edit/:id', 'checkAdminAccess', '\Prolio\Controller\PageBackend:edit')->name('page_edit');
    $app->post('/page/process/(:id)', 'checkAdminAccess', '\Prolio\Controller\PageBackend:process')->name('page_process');

    $app->map('/settings', 'checkAdminAccess', '\Prolio\Controller\SettingsBackend:index')->via('GET', 'POST')->name('settings');
});
