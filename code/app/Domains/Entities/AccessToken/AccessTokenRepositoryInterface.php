<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\AccessToken;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface as LeagueAccessTokenRepositoryInterface;

interface AccessTokenRepositoryInterface extends LeagueAccessTokenRepositoryInterface
{
    public function getAccessTokenById(string $id): ?AccessToken;
}
