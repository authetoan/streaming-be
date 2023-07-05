<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Controllers;

use DateInterval;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Travis\StreamingBackend\Domains\Entities\AccessToken\AccessToken;
use Travis\StreamingBackend\Domains\Entities\Grant\CustomClientCredentialsGrant;
use Travis\StreamingBackend\Domains\Entities\Grant\CustomPasswordGrant;
use Travis\StreamingBackend\Domains\Entities\User\UserRepositoryInterface;
use Travis\StreamingBackend\Domains\ValueObjects\UserVO;
use Travis\StreamingBackend\Exceptions\InvalidRequestException;
use Travis\StreamingBackend\Infrastructures\Persistency\Redis\Repositories\RefreshTokenRepository;
use Travis\StreamingBackend\Infrastructures\Services\AuthService;
use Travis\StreamingBackend\Infrastructures\Services\League\AuthorizationServer;

class AuthController extends BaseController
{
    public function __construct(
        private readonly AuthorizationServer $oauth_server,
        private readonly UserRepositoryInterface $user_repository,
        private readonly RefreshTokenRepository $refresh_token_repository,
        private readonly AuthService $authService,
        private readonly ResourceServer $resource_server
    ) {
    }

    public function login(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $grant = new CustomPasswordGrant($this->user_repository, $this->refresh_token_repository);
        $this->oauth_server->enableGrantType(
            $grant,
            new DateInterval(AccessToken::TOKEN_SESSION_LIFETIME)
        );
        $grant = new CustomClientCredentialsGrant();
        $this->oauth_server->enableGrantType(
            $grant,
            new DateInterval(AccessToken::TOKEN_SESSION_LIFETIME)
        );
        $result = $this->oauth_server->getResponseToAccessTokenRequest($request);
        return $this->responseJson($response, $result);
    }

    public function register(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $body = $request->getParsedBody();
        if (!isset($body['email']) || !isset($body['password']) || !isset($body['name'])) {
            throw new InvalidRequestException('Invalid request');
        }

        $userVO = new UserVO(
            email: $body['email'],
            password: $body['password'],
            name: $body['name']
        );

        $result = $this->authService->register($userVO);
        return $this->responseJson($response, $result);
    }
}
