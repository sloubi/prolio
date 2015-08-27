<?php

$app->get('/', function () use ($app) {
    $app->render('index.twig');
});
$app->get('/project/:id', '\Prolio\Controller\Project:get')->name('project');
$app->get('/projects', '\Prolio\Controller\Project:all')->name('projects');
