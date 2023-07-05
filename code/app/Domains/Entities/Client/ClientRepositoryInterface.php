<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Client;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface as LeagueClientRepositoryInterface;

interface ClientRepositoryInterface extends LeagueClientRepositoryInterface
{
    public function find(int $id): ?object;
    public function getClientEntity($clientIdentifier): ?ClientEntityInterface;
}
