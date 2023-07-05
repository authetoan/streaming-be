<?php

declare(strict_types = 1);

use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\MockExternalStreamingService;

return [
    'streaming' =>
    [
        'driver' => MockExternalStreamingService::class
    ]
];
