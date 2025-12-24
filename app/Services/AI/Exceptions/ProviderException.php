<?php

declare(strict_types=1);

namespace App\Services\AI\Exceptions;

class ProviderException extends AIException
{
    public function __construct(
        string $message = 'Blad komunikacji z usluga AI.',
        ?string $errorCode = 'provider_error',
        public readonly ?string $providerResponse = null,
    ) {
        parent::__construct($message, $errorCode);
    }
}
