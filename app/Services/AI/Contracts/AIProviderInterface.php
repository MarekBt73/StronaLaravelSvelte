<?php

declare(strict_types=1);

namespace App\Services\AI\Contracts;

use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\DTOs\AIResponse;

interface AIProviderInterface
{
    /**
     * Generuje tekst na podstawie promptu.
     */
    public function generate(AIRequest $request): AIResponse;

    /**
     * Sprawdza dostepnosc API (czy klucz jest skonfigurowany).
     */
    public function isAvailable(): bool;

    /**
     * Zwraca nazwe providera (do wyswietlenia w UI).
     */
    public function getName(): string;

    /**
     * Zwraca identyfikator providera.
     */
    public function getIdentifier(): string;

    /**
     * Zwraca liste dostepnych modeli.
     *
     * @return array<string, string>
     */
    public function getModels(): array;

    /**
     * Zwraca aktualnie uzyawny model.
     */
    public function getCurrentModel(): string;
}
