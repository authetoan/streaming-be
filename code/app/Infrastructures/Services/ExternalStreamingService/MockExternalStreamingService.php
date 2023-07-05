<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService;

use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ValueObjects\StreamRoomVO;

class MockExternalStreamingService implements ExternalStreamingServiceInterface
{
    private StreamRoomVO $streamRoomVO;

    public function __construct()
    {
        $this->streamRoomVO = new StreamRoomVO(
            room_id: rand(),
            record_url: null,
            stream_key: 'stream_key',
            created_at: '2021-01-01 00:00:00',
            updated_at: '2021-01-01 00:00:00',
        );
    }
    public function createStreaming(): StreamRoomVO
    {
        return $this->streamRoomVO;
    }

    public function deleteStreaming(int $room_id): StreamRoomVO
    {
        $this->streamRoomVO->setRecordUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        return $this->streamRoomVO;
    }
}
