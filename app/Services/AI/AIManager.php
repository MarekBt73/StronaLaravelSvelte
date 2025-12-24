<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\DTOs\AIResponse;
use App\Services\AI\Exceptions\AIException;
use App\Services\AI\Exceptions\RateLimitException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AIManager
{
    /**
     * @var array<string, AIProviderInterface>
     */
    private array $providers = [];

    private string $defaultProvider;

    public function __construct()
    {
        $this->defaultProvider = config('ai.default', 'gemini');
    }

    /**
     * Generuje tresc uzywajac domyslnego lub wskazanego providera.
     */
    public function generate(AIRequest $request, ?string $providerName = null): AIResponse
    {
        $providerName = $providerName ?? $this->defaultProvider;

        // Sprawdz rate limit przed wywolaniem
        if ($this->isRateLimited()) {
            return AIResponse::error(
                'Przekroczono dzienny limit zapytan AI. Sprobuj jutro.',
                'daily_rate_limit_exceeded'
            );
        }

        try {
            $provider = $this->getProvider($providerName);

            if (! $provider->isAvailable()) {
                return AIResponse::error(
                    "Provider '{$providerName}' nie jest skonfigurowany.",
                    'provider_not_configured'
                );
            }

            // Inkrementuj licznik zapytan
            $this->incrementRequestCount();

            $response = $provider->generate($request);

            // Loguj uzycie (bez tresci dla prywatnosci)
            Log::info('AI generation completed', [
                'provider' => $providerName,
                'action' => $request->action->value,
                'tokens' => $response->tokensUsed,
                'success' => $response->success,
            ]);

            return $response;

        } catch (RateLimitException $e) {
            Log::warning('AI rate limit hit', ['provider' => $providerName]);
            throw $e;
        } catch (AIException $e) {
            Log::error('AI generation failed', [
                'provider' => $providerName,
                'error' => $e->getMessage(),
                'code' => $e->errorCode,
            ]);
            throw $e;
        }
    }

    /**
     * Pobiera instancje providera.
     */
    public function getProvider(string $name): AIProviderInterface
    {
        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $config = config("ai.providers.{$name}");

        if (! $config || ! isset($config['class'])) {
            throw new AIException("Nieznany provider AI: {$name}", 'unknown_provider');
        }

        $providerClass = $config['class'];

        if (! class_exists($providerClass)) {
            throw new AIException("Klasa providera nie istnieje: {$providerClass}", 'provider_class_not_found');
        }

        $this->providers[$name] = new $providerClass();

        return $this->providers[$name];
    }

    /**
     * Zwraca liste dostepnych providerow.
     *
     * @return array<string, array{name: string, available: bool, model: string}>
     */
    public function getAvailableProviders(): array
    {
        $providers = [];
        $configuredProviders = config('ai.providers', []);

        foreach ($configuredProviders as $name => $config) {
            try {
                $provider = $this->getProvider($name);
                $providers[$name] = [
                    'name' => $provider->getName(),
                    'available' => $provider->isAvailable(),
                    'model' => $provider->getCurrentModel(),
                    'models' => $provider->getModels(),
                ];
            } catch (\Exception $e) {
                $providers[$name] = [
                    'name' => $name,
                    'available' => false,
                    'model' => '',
                    'models' => [],
                ];
            }
        }

        return $providers;
    }

    /**
     * Zwraca domyslnego providera.
     */
    public function getDefaultProvider(): string
    {
        return $this->defaultProvider;
    }

    /**
     * Ustawia domyslnego providera.
     */
    public function setDefaultProvider(string $provider): void
    {
        $this->defaultProvider = $provider;
    }

    /**
     * Sprawdza czy uzytkownik przekroczyl dzienny limit.
     */
    public function isRateLimited(): bool
    {
        if (! config('ai.rate_limit.enabled', true)) {
            return false;
        }

        $dailyLimit = config('ai.rate_limit.max_requests_per_day', 500);
        $currentCount = $this->getDailyRequestCount();

        return $currentCount >= $dailyLimit;
    }

    /**
     * Pobiera liczbe dzisiejszych zapytan.
     */
    public function getDailyRequestCount(): int
    {
        $cacheKey = $this->getDailyCountCacheKey();

        return (int) Cache::get($cacheKey, 0);
    }

    /**
     * Pobiera pozostala liczbe zapytan na dzis.
     */
    public function getRemainingRequests(): int
    {
        $dailyLimit = config('ai.rate_limit.max_requests_per_day', 500);
        $currentCount = $this->getDailyRequestCount();

        return max(0, $dailyLimit - $currentCount);
    }

    /**
     * Inkrementuje licznik zapytan.
     */
    private function incrementRequestCount(): void
    {
        $cacheKey = $this->getDailyCountCacheKey();
        $currentCount = $this->getDailyRequestCount();

        // Cache wygasa o polnocy
        $secondsUntilMidnight = now()->endOfDay()->diffInSeconds(now());

        Cache::put($cacheKey, $currentCount + 1, $secondsUntilMidnight);
    }

    /**
     * Generuje klucz cache dla dziennego licznika.
     */
    private function getDailyCountCacheKey(): string
    {
        return 'ai_requests_' . now()->format('Y-m-d');
    }
}
