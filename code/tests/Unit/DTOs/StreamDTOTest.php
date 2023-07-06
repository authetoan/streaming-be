<?php
namespace Test\Unit\DTOs;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\DTOs\StreamDTO;

class StreamDTOTest extends TestCase
{
    public function testGetters()
    {
        $user = $this->createMock(User::class);
        $created_at = new Carbon();
        $updated_at = new Carbon();
        $stream_dto = new StreamDTO(
            title: "title",
            uuid: "uuid",
            stream_key: "stream_key",
            room_id: 1,
            record_url: "record_url",
            is_active: true,
            user: $user,
            created_at: $created_at,
            updated_at: $updated_at
        );
        $this->assertEquals("title", $stream_dto->getTitle());
        $this->assertEquals("uuid", $stream_dto->getUuid());
        $this->assertEquals("stream_key", $stream_dto->getStreamKey());
        $this->assertEquals(1, $stream_dto->getRoomId());
        $this->assertEquals("record_url", $stream_dto->getRecordUrl());
        $this->assertTrue($stream_dto->isActive());
        $this->assertEquals($user,$stream_dto->getUser());
        $this->assertEquals($created_at,$stream_dto->getCreatedAt());
        $this->assertEquals($updated_at,$stream_dto->getUpdatedAt());

    }
}