<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\AccessToken;

use DateTimeImmutable;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

abstract class BaseCustomAccessToken implements BaseCustomAccessTokenEntityInterface
{
    use EntityTrait;
    use AccessTokenTrait;
    use TokenEntityTrait;

    protected array $custom_claims = [];

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addCustomClaim(string $key, $value): void
    {
        $this->custom_claims[$key] = $value;
    }

    private function convertToJWT(): UnencryptedToken
    {
        $this->initJwtConfiguration();

        $builder =  $this->jwtConfiguration->builder()
            ->permittedFor($this->getClient()->getIdentifier())
            ->identifiedBy($this->getIdentifier())
            ->issuedAt(new DateTimeImmutable())
            ->canOnlyBeUsedAfter(new DateTimeImmutable())
            ->expiresAt($this->getExpiryDateTime())
            ->relatedTo((string) $this->getUserIdentifier())
            ->withClaim('scopes', $this->getScopes());

        foreach ($this->custom_claims as $key => $value) {
            $builder = $builder->withClaim($key, $value);
        }

        return $builder->getToken($this->jwtConfiguration->signer(), $this->jwtConfiguration->signingKey());
    }
}
