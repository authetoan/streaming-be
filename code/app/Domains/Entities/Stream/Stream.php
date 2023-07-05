<?php

declare(strict_types = 1);

namespace  Travis\StreamingBackend\Domains\Entities\Stream;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Travis\StreamingBackend\Domains\Entities\User\User;

class Stream
{
    private readonly int $id;

    private readonly string $uuid;

    private string $stream_key;

    private int $room_id;

    private bool $is_active;

    private ?string $record_url;

    private DateTimeInterface $created_at;

    private DateTimeInterface $updated_at;

    public function __construct(
        private string $title,
        private User $user
    ) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->is_active = true;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->record_url = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStreamKey(): string
    {
        return $this->stream_key;
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function getRecordUrl(): ?string
    {
        return $this->record_url;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updated_at;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setStreamKey(string $stream_key): void
    {
        $this->stream_key = $stream_key;
    }

    public function setRoomId(int $room_id): void
    {
        $this->room_id = $room_id;
    }

    public function setActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }

    public function setRecordUrl(?string $record_url): void
    {
        $this->record_url = $record_url;
    }

    public function setUpdatedAt(DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
