<?php

declare(strict_types=1);

namespace App\Services\AI\DTOs;

use App\Services\AI\AIAction;

final readonly class AIRequest
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        public AIAction $action,
        public string $content,
        public array $options = [],
        public ?string $systemPrompt = null,
    ) {}

    /**
     * Tworzy request z tablicy (np. z formularza).
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            action: AIAction::from($data['action']),
            content: $data['content'] ?? '',
            options: $data['options'] ?? [],
            systemPrompt: $data['system_prompt'] ?? null,
        );
    }

    /**
     * Pobiera opcje z domyslnymi wartosciami.
     */
    public function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * Zwraca ton tekstu.
     */
    public function getTone(): string
    {
        return $this->getOption('tone', 'professional_medical');
    }

    /**
     * Zwraca dlugosc tekstu.
     */
    public function getLength(): string
    {
        return $this->getOption('length', 'medium');
    }

    /**
     * Czy dolaczac CTA.
     */
    public function shouldIncludeCta(): bool
    {
        return (bool) $this->getOption('include_cta', true);
    }

    /**
     * Zwraca jezyk docelowy (dla tlumaczenia).
     */
    public function getTargetLanguage(): string
    {
        return $this->getOption('target_language', 'en');
    }
}
