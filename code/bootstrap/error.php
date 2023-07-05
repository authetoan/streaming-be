<?php

declare(strict_types = 1);

use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Travis\StreamingBackend\Exceptions\BaseClientException;

return static function (App $app): App {
    $customErrorHandler = function (
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
    ) use ($app) {

        $payload = ['error' => $exception->getMessage()];
        if (isset($_ENV['DEBUG']) && $_ENV['DEBUG'] == 'true') {
            $payload['trace]'] = $exception->getTrace();
        }
        $response = $app->getResponseFactory()->createResponse();
        $response->getBody()->write(
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        );
        if ($exception instanceof BaseClientException) {
            return $response->withStatus($exception->getCode())->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    };
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setDefaultErrorHandler($customErrorHandler);

    return $app;
};
