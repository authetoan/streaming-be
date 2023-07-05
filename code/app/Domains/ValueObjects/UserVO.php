<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Domains\ValueObjects;

class UserVO
{
    public function __construct(
        private string $email,
        private string $password,
        private string $name,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
