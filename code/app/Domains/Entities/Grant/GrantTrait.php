<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Grant;

use DateInterval;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Travis\StreamingBackend\Domains\Entities\AccessToken\BaseCustomAccessToken;

trait GrantTrait
{
    //phpcs:disable
    protected function issueAccessToken(
        DateInterval $accessTokenTTL,
        ClientEntityInterface $client,
        $userIdentifier,
        array $scopes = []
    ): BaseCustomAccessToken
    {
        $access_token = parent::issueAccessToken($accessTokenTTL, $client, $userIdentifier, $scopes);
        // Custom here
        return $access_token;
    }
    //phpcs:enable
}
