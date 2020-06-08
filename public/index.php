<?php

require __DIR__ . '/../bootstrap.php';

$app = \DIExample\Application::create();

$app->get('/', function () {
    echo "Hello world";
});

$app->get('/hello/{name}', function ($args) {
    $name = $args['name'];
    echo "hello $name";
});

$app->run();
