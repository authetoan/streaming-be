<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Services;

use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\Exceptions\StreamingNotFoundException;
use Travis\StreamingBackend\Exceptions\UserHaveActiveStreamingException;
use Travis\StreamingBackend\Exceptions\UserNotHaveActiveStreamingException;
use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ExternalStreamingServiceInterface;

class StreamService
{
    public function __construct(
        private StreamRepositoryInterface $stream_repository,
        private ExternalStreamingServiceInterface $externalStreamingService
    ) {
    }

    public function startStreaming(User $user, string $title): Stream
    {
        $is_user_have_active_streaming = $this->stream_repository->findUserActiveStream(user: $user);
        if ($is_user_have_active_streaming) {
            throw new UserHaveActiveStreamingException();
        }
        $stream = new Stream(title: $title, user: $user);
        $stream_data_vo = $this->externalStreamingService->createStreaming();
        $stream->setRoomId(room_id: $stream_data_vo->getRoomId());
        $stream->setStreamKey(stream_key: $stream_data_vo->getStreamKey());
        $this->stream_repository->save(stream: $stream);
        return $stream;
    }

    public function stopStreaming(User $user): Stream
    {
        $stream = $this->stream_repository->findUserActiveStream(user: $user);
        if ($stream === null) {
            throw new UserNotHaveActiveStreamingException();
        }
        $streamRoomVO = $this->externalStreamingService->deleteStreaming(room_id: $stream->getRoomId());
        $stream->setActive(is_active: false);
        $stream->setRecordUrl(record_url: $streamRoomVO->getRecordUrl());
        $this->stream_repository->save($stream);
        return $stream;
    }
}
