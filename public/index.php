<?php

require __DIR__ . '/../bootstrap.php';

$app = \DIExample\Application::create();

// Create a dummy service.
$MyexternalService = new stdClass();
// Add the dummy service in the container.
$app->useService('my_external_service', $MyexternalService);

$app->get('/', function ($args) {
    // Get the container.
    /** @var \DI\Container $container */
    $container = $args['_container'];
    // Get the dummy service from the container.
    $dummy = $container->get('my_external_service');

    echo "Hello world";
});

$app->get('/hello/{name}', function ($args) {
    $name = $args['name'];
    echo "hello $name";
});

$app->run();
