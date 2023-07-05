<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Redis\Repositories;

use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Redis;
use Travis\StreamingBackend\Domains\Entities\RefreshToken\RefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Carbon\Carbon;

/**
 *
 */
class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    private const TOKEN_KEY_PREFIX = 'rt_';

    public function __construct(private Redis $redis)
    {
    }

    public function getNewRefreshToken(): RefreshToken|RefreshTokenEntityInterface|null
    {
        return new RefreshToken();
    }


    /**
     * @throws InvalidArgumentException
     */
    // phpcs:disable
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
    }
    // phpcs:enable

    /**
     * @throws InvalidArgumentException
     */
    // phpcs:disable
    public function revokeRefreshToken($tokenId): void
    {
    }
    // phpcs:enable

    /**
     * @throws InvalidArgumentException
     */
    // phpcs:disable
    public function isRefreshTokenRevoked($tokenId): bool
    {
        return false;
    }

    // phpcs:enable


    public function generateCacheKeyByTokenId(string $token_id): string
    {
        return self::TOKEN_KEY_PREFIX . $token_id;
    }
}
