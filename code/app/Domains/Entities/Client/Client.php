<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Client;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Exception;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Travis\StreamingBackend\Domains\Entities\Permission\Permission;

class Client implements ClientEntityInterface
{
    protected int $id;
    private string $identifier;
    protected string|array $redirect_uri;
    protected DateTimeInterface $created_at;
    protected DateTimeInterface $updated_at;
    protected bool $confidential = true;
    private Collection $permissions;

    public function __construct(
        protected string $name,
        protected string $client_id,
        protected ?string $client_secret = null,
        protected ?string $provider = null
    ) {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = $this->created_at;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    protected function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }


    protected function setClientId(string $client_id): void
    {
        $this->client_id = $client_id;
    }

    public function getClientId(): string
    {
        return $this->client_id;
    }

    protected function setClientSecret(?string $client_secret = null): void
    {
        $this->client_secret = $client_secret;
    }

    public function getClientSecret(): ?string
    {
        return $this->client_secret;
    }


    public function setRedirectUri(string $uri): void
    {
        $this->redirect_uri = $uri;
    }

    public function getRedirectUri(): string|array //TODO
    {
        return $this->redirect_uri;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }


    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setConfidential(): void
    {
        $this->confidential = true;
    }

    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions->add($permission);
        $permission->addClient($this);
    }

    public function removePermission(Permission $permission): void
    {
        $this->permissions->removeElement($permission);
        $permission->removeClient($this);
    }
}
