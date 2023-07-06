<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\User;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use JsonSerializable;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Ramsey\Uuid\Uuid;
use Travis\StreamingBackend\Domains\Entities\Permission\Permission;
use Travis\StreamingBackend\Domains\Entities\Role\Role;

class User implements UserEntityInterface, JsonSerializable
{
    private readonly int $id;
    private readonly string $uuid;

    private Collection $permissions;
    private Collection $roles;

    private DateTimeInterface $created_at;
    private DateTimeInterface $updated_at;


    public function __construct(
        private string  $email,
        private string  $password,
        private string  $name
    ) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->password = password_hash($password, null);
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->roles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    public function getIdentifier(): int
    {
        return $this->id;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): void
    {
        $this->roles->add($role);
        $role->addUser($this);
    }

    public function removeRole(Role $role): void
    {
        $this->roles->removeElement($role);
        $role->removeUser($this);
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions->add($permission);
    }

    public function removePermission(Permission $permission): void
    {
        $this->permissions->removeElement($permission);
    }

    public function getCombinePermission(): array
    {
        $permissions = [];
        foreach ($this->getRoles() as $role) {
            foreach ($role->getPermissions() as $permission) {
                $permissions[] = $permission;
            }
        }
        foreach ($this->getPermissions() as $permission) {
            $permissions[] = $permission;
        }

        return array_unique($permissions, SORT_REGULAR);
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getIdentifier(),
            "name" => $this->getName(),
            "email" => $this->getEmail(),
        ];
    }
}
