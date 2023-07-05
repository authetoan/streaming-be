<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Grant;

use League\OAuth2\Server\Grant\RefreshTokenGrant;

class CustomRefreshTokenGrant extends RefreshTokenGrant
{
    use GrantTrait;
}
