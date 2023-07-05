<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Redis\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Redis;
use Travis\StreamingBackend\Domains\Entities\AccessToken\AccessToken;
use Travis\StreamingBackend\Domains\Entities\AccessToken\AccessTokenRepositoryInterface;

/**
 *
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private const TOKEN_KEY_PREFIX = 'at_';

    public function __construct(private Redis $redis)
    {
    }

    /**
     * @return void
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $key = $this->generateCacheKeyByTokenId($accessTokenEntity->getIdentifier());
        $this->redis->set($key, (string) $accessTokenEntity, 600);
    }

    /**
     * @return void
     */
    public function revokeAccessToken($tokenId)
    {
        $key = $this->generateCacheKeyByTokenId($tokenId);
        $this->redis->del($key);
    }

    public function isAccessTokenRevoked($tokenId): bool
    {
        $key = $this->generateCacheKeyByTokenId($tokenId);
        return  !$this->redis->exists($key);
        ;
    }

    public function getAccessTokenById(string $id): ?AccessToken
    {
        $key = $this->generateCacheKeyByTokenId($id);
        $result = $this->redis->get($key);

        if (!($result instanceof AccessToken)) {
            return null;
        }

        return $result;
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessToken
    {
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        // Add policies in access token
        // $accessToken->addScope($token_scope);
        $accessToken->setUserIdentifier($userIdentifier);
        return $accessToken;
    }

    public function generateCacheKeyByTokenId(string $token_id): string
    {
        return self::TOKEN_KEY_PREFIX . $token_id;
    }
}
