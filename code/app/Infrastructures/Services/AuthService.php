<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services;

use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\Domains\Entities\User\UserRepositoryInterface;
use Travis\StreamingBackend\Domains\ValueObjects\UserVO;
use Travis\StreamingBackend\Exceptions\UserExistsException;

class AuthService
{
    public function __construct(private readonly UserRepositoryInterface $user_repository)
    {
    }

    public function register(UserVO $userVO): User
    {
        $current_user = $this->user_repository->findByEmail($userVO->getEmail());
        if ($current_user) {
            throw new UserExistsException();
        }
        $user = new User(
            email: $userVO->getEmail(),
            password: $userVO->getPassword(),
            name: $userVO->getName()
        );
        $this->user_repository->save($user);
        return $user;
    }
}
