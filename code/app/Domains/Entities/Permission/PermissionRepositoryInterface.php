<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Permission;

interface PermissionRepositoryInterface
{
    public function save(Permission $permission): void;
    public function findByName(string $name): object|null;
}
