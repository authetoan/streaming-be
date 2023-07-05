<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\RefreshToken;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;

/**
 * RefreshToken entity
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use EntityTrait;
    use RefreshTokenTrait;

    /**
     * Token session lifetime.
     * Refresh tokens will expire after 1 month.
     */
    public const REFRESH_TOKEN_SESSION_LIFETIME = 'P1M';
}
