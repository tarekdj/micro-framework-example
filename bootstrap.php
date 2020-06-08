<?php
// Require the autoloader.
require __DIR__ . '/vendor/autoload.php';

// Load the env. vars.
try
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $dotenv->required('APP_ENV')->notEmpty();
}
catch(\Dotenv\Exception\InvalidPathException $e)
{
    echo 'No .env file found!';
    die;
}

// Disable error reporting on prod.
if (getenv('APP_ENV') !== 'production') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
