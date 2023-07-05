<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Role;

use Doctrine\Common\Collections\Collection;
use Travis\StreamingBackend\Domains\Entities\Permission\Permission;
use Travis\StreamingBackend\Domains\Entities\User\User;

class Role
{
    private readonly int $id;

    private Collection $permissions;
    private Collection $users;

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

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions->add($permission);
        $permission->addRole($this);
    }

    public function removePermission(Permission $permission): void
    {
        $this->permissions->removeElement($permission);
        $permission->removeRole($this);
    }

    public function addUser(User $user): void
    {
        $this->users->add($user);
    }

    public function removeUser(User $user): void
    {
        $this->users->removeElement($user);
    }
}
