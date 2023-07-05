<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Exceptions;

class StreamingNotFoundException extends BaseClientException
{
    public function __construct(
        string $message = 'Streaming not found',
        int $code = 400,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
