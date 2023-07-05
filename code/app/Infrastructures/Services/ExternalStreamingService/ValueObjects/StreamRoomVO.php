<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ValueObjects;

class StreamRoomVO
{
    public function __construct(
        private int    $room_id,
        private ?string $record_url,
        private string $stream_key,
        private string $created_at,
        private string $updated_at,
    ) {
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function getStreamKey(): string
    {
        return $this->stream_key;
    }

    public function getRecordUrl(): string|null
    {
        return $this->record_url;
    }

    public function setRecordUrl(string $record_url): void
    {
        $this->record_url = $record_url;
    }
}
