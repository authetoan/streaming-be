<?php

declare(strict_types = 1);


return [
    'oauth2' => [
        'private_key' => __DIR__.'/../'.$_ENV['OAUTH_PRIVATE_KEY'],
        'public_key' => __DIR__.'/../'.$_ENV['OAUTH_PUBLIC_KEY'],
        'encrypt_key' => $_ENV['OAUTH_ENCRYPT_KEY'],
    ]
];
