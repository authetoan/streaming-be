<?php
namespace Test\Unit\Infrastructures\Services;

use PHPUnit\Framework\TestCase;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\Exceptions\UserHaveActiveStreamingException;
use Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories\StreamRepository;
use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ExternalStreamingServiceInterface;
use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ValueObjects\StreamRoomVO;
use Travis\StreamingBackend\Infrastructures\Services\StreamService;
use \Mockery;
class StreamServiceTest extends TestCase
{
    public function testStartStreaming(){
        $stream_repository = $this->createMock(StreamRepository::class);
        $stream_room_vo = $this->createMock(StreamRoomVO::class);
        $external_streaming_service = $this->createMock(ExternalStreamingServiceInterface::class);
        $external_streaming_service->expects($this->once())
            ->method('createStreaming')
            ->willReturn(
                $stream_room_vo
            );
        $stream_repository->expects($this->once())->method('findUserActiveStream')->willReturn(null);
        $stream_repository->expects($this->once())->method('save');
        $streamingService = new StreamService(
            stream_repository: $stream_repository,
            externalStreamingService: $external_streaming_service);
        $title = "title";
        $user = $this->createMock(User::class);
        $result = $streamingService->startStreaming(user: $user,title: $title);
        $this->assertInstanceOf(Stream::class,$result);
    }

    public function testStartStreamingButUserAlreadyStream(){
        $stream_repository = $this->createMock(StreamRepository::class);
        $external_streaming_service = $this->createMock(ExternalStreamingServiceInterface::class);
        $stream = $this->createMock(Stream::class);
        $stream_repository->expects($this->once())->method('findUserActiveStream')->willReturn($stream);
        $streamingService = new StreamService(
            stream_repository: $stream_repository,
            externalStreamingService: $external_streaming_service);
        $title = "title";
        $user = $this->createMock(User::class);
        $this->expectException(UserHaveActiveStreamingException::class);
        $streamingService->startStreaming(user: $user,title: $title);
    }
}