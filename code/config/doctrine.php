<?php

declare(strict_types = 1);

return [
    "doctrine" => [
        "mapping_path" => __DIR__ . "/../app/Infrastructures/Persistency/Doctrine/Mapping",
        "host" => $_ENV['DB_HOST'],
        "driver" => $_ENV['DB_DRIVER'],
        "user" => $_ENV['DB_USERNAME'],
        "password" => $_ENV['DB_PASSWORD'],
        "dbname" => $_ENV['DB_DATABASE'],
    ]
];
