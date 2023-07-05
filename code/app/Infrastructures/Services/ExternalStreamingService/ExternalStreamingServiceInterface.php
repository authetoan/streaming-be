<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService;

use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ValueObjects\StreamRoomVO;

interface ExternalStreamingServiceInterface
{
    public function createStreaming(): StreamRoomVO;

    public function deleteStreaming(int $room_id): StreamRoomVO;
}
