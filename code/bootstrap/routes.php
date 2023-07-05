<?php

declare(strict_types = 1);

use League\OAuth2\Server\Middleware\ResourceServerMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use Travis\StreamingBackend\Controllers\AuthController;
use Travis\StreamingBackend\Controllers\StreamController;
use Travis\StreamingBackend\Middleware\AuthMiddleware;

return static function (App $app): App {
    $app->post('/', [AuthController::class ,'login']);
    $app->group('/auth', function (RouteCollectorProxy $route) use ($app) {
        $route->post('/login', [AuthController::class ,'login']);
        $route->post('/register', [AuthController::class ,'register']);
    });
    $app->group('/streamer', function (RouteCollectorProxy $route) use ($app) {
            $route->post('/start_room', [StreamController::class ,'startStream']);
            $route->post('/close_room', [StreamController::class ,'stopStream']);
    })->addMiddleware(new AuthMiddleware($app->getContainer()));
    $app->group('/audience', function (RouteCollectorProxy $route) use ($app) {
            $route->get('/livestreams', [StreamController::class ,'index']);
            $route->get('/livestreams/{uuid}', [StreamController::class ,'show']);
    })->addMiddleware(new AuthMiddleware($app->getContainer()));
    return $app;
};
