<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Exceptions;

abstract class BaseClientException extends \Exception
{
    public function __construct(
        string $message = 'Request Error',
        int $code = 400,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
