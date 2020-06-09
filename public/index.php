<?php

require __DIR__ . '/../bootstrap.php';

$app = \DIExample\Application::create();

$app->get('/', function ($args) {
    // Get the container.
    /** @var \DI\Container $container */
    $container = $args['_container'];
    // use $container->get('SERVICE_NAME') to load a service.
    echo "Hello world";
});

$app->get('/hello/{name}', function ($args) {
    $name = $args['name'];
    echo "hello $name";
});

$app->run();
