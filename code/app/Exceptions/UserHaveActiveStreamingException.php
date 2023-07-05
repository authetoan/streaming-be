<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Exceptions;

class UserHaveActiveStreamingException extends BaseClientException
{
    public function __construct(
        string $message = 'User already streaming',
        int $code = 400,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
