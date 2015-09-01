<?php

$app->get('/', function () use ($app) {
    $app->render('index.twig');
});

$app->get('/project/:id', '\Prolio\Controller\Project:get')->name('project');
$app->get('/projects', '\Prolio\Controller\Project:all')->name('projects');

$app->get('/contact', '\Prolio\Controller\Contact:get')->name('contact');
$app->post('/contact', '\Prolio\Controller\Contact:post');
