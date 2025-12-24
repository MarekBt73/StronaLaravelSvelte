<?php

declare(strict_types=1);

namespace App\Services\AI\Exceptions;

class RateLimitException extends AIException
{
    public function __construct(
        string $message = 'Przekroczono limit zapytan. Sprobuj ponownie pozniej.',
        public readonly ?int $retryAfter = null,
    ) {
        parent::__construct($message, 'rate_limit_exceeded');
    }
}
