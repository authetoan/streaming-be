<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\DTOs;

use JsonSerializable;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\User\User;

class StreamsListDTO implements JsonSerializable
{
    private array $streams = [];

    public function __construct(array $streams)
    {
        /* @var Stream $stream  */
        foreach ($streams as $stream) {
            $this->streams[] = new AudienceStreamDTO(
                title: $stream->getTitle(),
                uuid: $stream->getUuid(),
                room_id: $stream->getRoomId(),
                record_url: $stream->getRecordUrl(),
                user: $stream->getUser()
            );
        }
    }

    public function __toArray(): array
    {
        return $this->streams;
    }

    public function jsonSerialize(): mixed
    {
        return $this->streams;
    }
}
