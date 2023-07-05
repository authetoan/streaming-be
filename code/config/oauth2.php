<?php

declare(strict_types = 1);

$rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);

return [
    'oauth2' => [
        'private_key' => $rootDir.'/../'.$_ENV['OAUTH_PRIVATE_KEY'],
        'public_key' => $rootDir.'/../'.$_ENV['OAUTH_PUBLIC_KEY'],
        'encrypt_key' => $_ENV['OAUTH_ENCRYPT_KEY'],
    ]
];
