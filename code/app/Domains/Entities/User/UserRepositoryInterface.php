<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\Entities\User;

use Travis\StreamingBackend\Domains\ValueObjects\ResetPasswordVO;

interface UserRepositoryInterface extends \League\OAuth2\Server\Repositories\UserRepositoryInterface
{
    public function save(User $user): void;
    public function find(int $id): ?User;
    public function findOneByOrCnd(array $conditions): object|null;
    public function findByEmail(string $email): ?User;
}
