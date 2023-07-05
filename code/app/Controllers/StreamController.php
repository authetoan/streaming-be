<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Controllers;

use http\Client\Curl\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\UserRepositoryInterface;
use Travis\StreamingBackend\DTOs\AudienceStreamDTO;
use Travis\StreamingBackend\DTOs\StreamDTO;
use Travis\StreamingBackend\DTOs\StreamsListDTO;
use Travis\StreamingBackend\Exceptions\StreamingNotFoundException;
use Travis\StreamingBackend\Infrastructures\Services\StreamService;

class StreamController extends BaseController
{
    public function __construct(
        private UserRepositoryInterface $user_repostiory,
        private StreamRepositoryInterface $stream_repository,
        private StreamService $stream_service
    ) {
    }

    public function index(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $limit = (int) $request->getQueryParams()['limit'] ?? 10;
        $offset = (int) $request->getQueryParams()['offset'] ?? 0;
        $result = $this->stream_repository->findAllActiveStream(limit: $limit, offset: $offset);
        $streamsListDto = new StreamsListDTO(streams: $result);
        return $this->responseJson($response, $streamsListDto);
    }

    public function show(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $uuid = $args['uuid'];
        $result = $this->stream_repository->findByUuid($uuid);
        if (!$result) {
            throw new StreamingNotFoundException();
        }
        $streamDto = new AudienceStreamDTO(
            title: $result->getTitle(),
            uuid: $result->getUuid(),
            room_id: $result->getRoomId(),
            record_url: $result->getRecordUrl(),
            user: $result->getUser()
        );
        return $this->responseJson($response, $streamDto);
    }

    public function startStream(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $user_id = (int) $request->getAttribute('oauth_user_id');
        $user = $this->user_repostiory->find($user_id);
        $title = $request->getParsedBody()['title'];
        $stream = $this->stream_service->startStreaming(user: $user, title:  $title);
        $result = new StreamDTO(
            title: $stream->getTitle(),
            uuid: $stream->getUuid(),
            stream_key: $stream->getStreamKey(),
            room_id: $stream->getRoomId(),
            record_url: $stream->getRecordUrl(),
            is_active: $stream->isActive(),
            user: $stream->getUser(),
            created_at: $stream->getCreatedAt(),
            updated_at: $stream->getUpdatedAt()
        );

        return $this->responseJson($response, $result);
    }

    public function stopStream(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $user_id = (int) $request->getAttribute('oauth_user_id');
        $user = $this->user_repostiory->find($user_id);
        $stream = $this->stream_service->stopStreaming(user: $user);
        $result = new StreamDTO(
            title: $stream->getTitle(),
            uuid: $stream->getUuid(),
            stream_key: $stream->getStreamKey(),
            room_id: $stream->getRoomId(),
            record_url: $stream->getRecordUrl(),
            is_active: $stream->isActive(),
            user: $stream->getUser(),
            created_at: $stream->getCreatedAt(),
            updated_at: $stream->getUpdatedAt()
        );
        return $this->responseJson($response, $result);
    }
}
