<?php

$app = require __DIR__.'/bootstrap/app.php';

return [
    "host" => $_ENV['DB_HOST'],
    "driver" => $_ENV['DB_DRIVER'],
    "user" => $_ENV['DB_USERNAME'],
    "password" => $_ENV['DB_PASSWORD'],
    "dbname" => $_ENV['DB_DATABASE'],
];
