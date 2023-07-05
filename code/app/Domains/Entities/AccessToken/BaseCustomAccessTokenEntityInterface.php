<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\AccessToken;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

interface BaseCustomAccessTokenEntityInterface extends AccessTokenEntityInterface
{
    public function addCustomClaim(string $key, $value): void;
}
