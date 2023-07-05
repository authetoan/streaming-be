<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Exceptions;

class InvalidRequestException extends BaseClientException
{
    public function __construct(
        string $message = 'Invalid Request',
        int $code = 400,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
