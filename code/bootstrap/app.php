<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;

if($_ENV['APP_ENV'] == 'testing') {
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__.'/../', '.env.testing');
}
else
{
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__.'/../');
}
$dotenv->load();


return static function (): \Slim\App {

    $container = require __DIR__ . "/container.php";
    $routes = require __DIR__ . "/routes.php";
    $middleware = require __DIR__ . "/middleware.php";
    $error = require __DIR__ . "/error.php";
    $app = AppFactory::createFromContainer($container());
    $app = $middleware($app);
    $app = $error($app);
    return $routes($app);
};
