<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services\League;

use JsonException;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\AuthorizationServer as LeagueAuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;

class AuthorizationServer extends LeagueAuthorizationServer
{
    /**
     * @throws OAuthServerException|JsonException
     */
    // phpcs:disable
    public function getResponseToAccessTokenRequest(ServerRequestInterface $request): array
    {
        foreach ($this->enabledGrantTypes as $grantType) {
            if (!$grantType->canRespondToAccessTokenRequest($request)) {
                continue;
            }
            $tokenResponse = $grantType->respondToAccessTokenRequest(
                $request,
                $this->getResponseType(),
                $this->grantTypeAccessTokenTTL[$grantType->getIdentifier()]
            );

            if ($tokenResponse instanceof BearerTokenResponse) {

                return $tokenResponse->generateResponse();
            }
        }

        throw OAuthServerException::unsupportedGrantType();
    }
    // phpcs:enable
}
