<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\DTOs;

use JsonSerializable;
use Travis\StreamingBackend\Domains\Entities\User\User;

class AudienceStreamDTO implements JsonSerializable
{
    public function __construct(
        private string $title,
        private string $uuid,
        private int $room_id,
        private ?string $record_url,
        private User $user
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getUser(): User
    {
        return $this->user;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function getRecordUrl(): string|null
    {
        return $this->record_url;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'uuid' => $this->uuid,
            'room_id' => $this->room_id,
            'record_url' => $this->record_url,
            'user' => $this->user->getName(),
            'user_uuid' => $this->user->getUuid()
        ];
    }
}
