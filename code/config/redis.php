<?php

declare(strict_types = 1);

return [
    "redis" => [
        "host" => $_ENV['REDIS_HOST'],
        "port" => (int) $_ENV['REDIS_PORT'],
        "password" => $_ENV['REDIS_PASSWORD'],
        "database" => $_ENV['REDIS_DATABASE'],
    ]
];
