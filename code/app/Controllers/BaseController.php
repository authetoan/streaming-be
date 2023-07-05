<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Controllers;

use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    protected function responseJson(ResponseInterface $response, mixed $result): ResponseInterface
    {
        $response->getBody()->write(json_encode($result));
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
