<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\Stream;

use Travis\StreamingBackend\Domains\Entities\User\User;

interface StreamRepositoryInterface
{
    public function save(Stream $stream): void;

    public function find(int $id): ?Stream;

    public function findByUuid(string $uuid): ?Stream;

    public function findAllActiveStream(int $limit, int $offset): array;

    public function findUserActiveStream(User $user): ?Stream;
}
