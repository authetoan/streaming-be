<?php

declare(strict_types = 1);

use Slim\App;

return static function (App $app): App {
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    return $app;
};
