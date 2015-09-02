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
        ),
        'user' => array(
            'email' => 'test@test.com',
            // password_hash('test', PASSWORD_DEFAULT);
            'password' => '$2y$10$CKsSStl.9WKjeUXU5Dblb.5HJcd7GDhTe.WRgRbUCEFvbK4VqSNxK'
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
        ),
        'user' => array(
            'email' => 'test@test.com'
        )
    ));
});