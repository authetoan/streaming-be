<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Middleware;

use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Travis\StreamingBackend\Exceptions\UnauthorizedException;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $resource_server = $this->container->get(ResourceServer::class);
        try {
            /* @var ResourceServer $resource_server */
            $request = $resource_server->validateAuthenticatedRequest($request);
        } catch (\Exception $e) {
            throw new UnauthorizedException();
        }
        return $handler->handle($request);
    }
}
