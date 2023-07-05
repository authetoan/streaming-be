<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\DTOs;

use DateTimeInterface;
use JsonSerializable;
use Travis\StreamingBackend\Domains\Entities\User\User;

class StreamDTO implements JsonSerializable
{
    public function __construct(
        private string $title,
        private string $uuid,
        private string $stream_key,
        private int $room_id,
        private ?string $record_url,
        private bool $is_active,
        private User $user,
        private DateTimeInterface $created_at,
        private DateTimeInterface $updated_at
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getStreamKey(): string
    {
        return $this->stream_key;
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function getRecordUrl(): ?string
    {
        return $this->record_url;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'title' => $this->title,
            'uuid' => $this->uuid,
            'stream_key' => $this->stream_key,
            'room_id' => $this->room_id,
            'record_url' => $this->record_url,
            'is_active' => $this->is_active,
            'user' => $this->user->getName(),
            'user_uuid' => $this->user->getUuid(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
