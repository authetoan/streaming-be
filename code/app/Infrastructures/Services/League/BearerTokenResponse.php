<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services\League;

use JsonException;
use LogicException;
use League\OAuth2\Server\ResponseTypes\BearerTokenResponse as LeagueBearerTokenResponse;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

class BearerTokenResponse extends LeagueBearerTokenResponse
{
    /**
     * @throws JsonException
     */
    // phpcs:disable
    public function generateResponse(): array
    {
        $expireDateTime = $this->accessToken->getExpiryDateTime()->getTimestamp();
        $access_token_scopes = $this->accessToken->getScopes();
        $scopes = [];
        foreach ($access_token_scopes as $scope)
        {
            $scopes[] = $scope->getIdentifier();
        }
        $responseParams = [
            'token_type'   => 'Bearer',
            'expires_in'   => $expireDateTime - \time(),
            'access_token' => (string) $this->accessToken,
            'scope' => $scopes,
            'token_id' => $this->accessToken->getIdentifier()  // Only way to see the token_id
        ];

        //@@@ TODO WARNING - Scopes were cleaned! Check!
        if ($this->refreshToken instanceof RefreshTokenEntityInterface) {
            $refreshTokenPayload = json_encode([
                'client_id' => $this->accessToken->getClient()->getIdentifier(),
                'refresh_token_id' => $this->refreshToken->getIdentifier(),
                'access_token_id' => $this->accessToken->getIdentifier(),
                'scopes' => $this->accessToken->getScopes(),
                'user_id' => $this->accessToken->getUserIdentifier(),
                'expire_time' => $this->refreshToken->getExpiryDateTime()->getTimestamp(),
            ], JSON_THROW_ON_ERROR);

            if ($refreshTokenPayload === false) {
                throw new LogicException('Error encountered JSON encoding the refresh token payload');
            }

            $responseParams['refresh_token'] = $this->encrypt($refreshTokenPayload);
        }

        $responseParams = array_merge($this->getExtraParams($this->accessToken), $responseParams);

        if ($responseParams === false) {
            throw new LogicException('Error encountered JSON encoding response parameters');
        }
        return $responseParams;
    }
    // phpcs:enable
}
