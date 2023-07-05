<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Grant;

use DateInterval;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\RequestAccessTokenEvent;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\RequestRefreshTokenEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;

class CustomPasswordGrant extends PasswordGrant
{
    use GrantTrait;

    /**
     * {@inheritdoc}
     * @throws OAuthServerException
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        DateInterval $accessTokenTTL
    ): ResponseTypeInterface {
        // Validate request
        $client = $this->validateClient($request);

        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));

        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes(
            $scopes,
            $this->getIdentifier(),
            $client,
            $user->getIdentifier()
        );

        // Issue and persist new access token
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $accessToken->addCustomClaim('sub_info', [
            'uuid' => $user->getUuid(),
            'name' => $user->getName(),
        ]);

        $this->getEmitter()->emit(
            new RequestAccessTokenEvent(
                RequestEvent::ACCESS_TOKEN_ISSUED,
                $request,
                $accessToken
            )
        );

        $responseType->setAccessToken($accessToken);

        // Issue and persist new refresh token if given
        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(
                new RequestRefreshTokenEvent(
                    RequestEvent::REFRESH_TOKEN_ISSUED,
                    $request,
                    $refreshToken
                )
            );

            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;
    }
}
