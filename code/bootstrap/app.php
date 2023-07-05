<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
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
