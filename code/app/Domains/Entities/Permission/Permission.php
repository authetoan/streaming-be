<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Permission;

use Doctrine\Common\Collections\Collection;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Slim\Exception\HttpException;
use Travis\StreamingBackend\Domains\Entities\Client\Client;
use Travis\StreamingBackend\Domains\Entities\Role\Role;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Psr\Http\Message\ServerRequestInterface;

class Permission implements ScopeEntityInterface
{

    private readonly int $id;
    private Collection $roles;
    private Collection $users;
    private Collection $clients;

    public function __construct(
        private string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIdentifier(): string
    {
        return $this->name;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): void
    {
        $this->roles->add($role);
    }

    public function removeRole(Role $role): void
    {
        $this->roles->removeElement($role);
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        $this->users->add($user);
    }

    public function jsonSerialize(): array
    {
        return ["name" => $this->getIdentifier()];
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): void
    {
        $this->clients->add($client);
    }

    public function removeClient(Client $client): void
    {
        $this->clients->removeElement($client);
    }
}
