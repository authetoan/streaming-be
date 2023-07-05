<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\AccessToken;

use League\OAuth2\Server\Entities\ClientEntityInterface;

class AccessToken extends BaseCustomAccessToken
{
    public const TOKEN_SESSION_LIFETIME = 'PT1H';

    // override the TokenEntityTrait and returns the proper type, useful for
    // static analysis to know what type we return
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }

    public function __serialize(): array
    {
        return [
            "identifier" => $this->identifier,
            "client" => $this->client,
            "scopes" => $this->scopes,
            "user_id" => $this->userIdentifier
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->identifier = $data["identifier"];
        $this->client = $data["client"];
        $this->scopes = $data["scopes"];
        $this->userIdentifier = $data["user_id"];
    }

    public function cleanScopes(): void
    {
        $this->scopes = [];
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }
}
