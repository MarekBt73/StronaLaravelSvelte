<?php

declare(strict_types=1);

namespace App\Services\AI\DTOs;

final readonly class AIResponse
{
    public function __construct(
        public bool $success,
        public string $content,
        public ?string $model = null,
        public ?string $provider = null,
        public ?int $tokensUsed = null,
        public ?int $promptTokens = null,
        public ?int $completionTokens = null,
        public ?string $error = null,
        public ?string $errorCode = null,
    ) {}

    /**
     * Tworzy odpowiedz sukcesu.
     */
    public static function success(
        string $content,
        string $model,
        string $provider,
        ?int $tokensUsed = null,
        ?int $promptTokens = null,
        ?int $completionTokens = null,
    ): self {
        return new self(
            success: true,
            content: $content,
            model: $model,
            provider: $provider,
            tokensUsed: $tokensUsed,
            promptTokens: $promptTokens,
            completionTokens: $completionTokens,
        );
    }

    /**
     * Tworzy odpowiedz bledu.
     */
    public static function error(string $message, ?string $code = null): self
    {
        return new self(
            success: false,
            content: '',
            error: $message,
            errorCode: $code,
        );
    }

    /**
     * Konwertuje do tablicy (dla JSON response).
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if (! $this->success) {
            return [
                'success' => false,
                'error' => [
                    'message' => $this->error,
                    'code' => $this->errorCode,
                ],
            ];
        }

        return [
            'success' => true,
            'data' => [
                'content' => $this->content,
                'model' => $this->model,
                'provider' => $this->provider,
                'tokens_used' => $this->tokensUsed,
                'prompt_tokens' => $this->promptTokens,
                'completion_tokens' => $this->completionTokens,
            ],
        ];
    }
}
