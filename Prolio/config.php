<?php

// Configs for mode "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'debug' => true,
        'database' => array(
            'host' => 'localhost',
            'port' => '',
            'name' => 'sloubi',
            'user' => 'root',
            'pass' => ''
        )
    ));
});

// Configs for mode "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'debug' => false,
        'database' => array(
            'host' => '',
            'port' => '',
            'name' => '',
            'user' => '',
            'pass' => ''
        )
    ));
});