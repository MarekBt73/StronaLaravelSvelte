<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AI\AIAction;
use App\Services\AI\AIManager;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\Exceptions\AIException;
use App\Services\AI\Exceptions\RateLimitException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function __construct(
        private readonly AIManager $aiManager,
    ) {}

    /**
     * Generuje tresc przy uzyciu AI.
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string'],
            'content' => ['required', 'string', 'max:50000'],
            'options' => ['sometimes', 'array'],
            'options.provider' => ['sometimes', 'string'],
            'options.tone' => ['sometimes', 'string'],
            'options.length' => ['sometimes', 'string'],
            'options.include_cta' => ['sometimes', 'boolean'],
            'options.target_language' => ['sometimes', 'string'],
            'options.title' => ['sometimes', 'string'],
        ]);

        try {
            $action = AIAction::from($validated['action']);
        } catch (\ValueError $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Nieznana akcja AI: ' . $validated['action'],
                    'code' => 'invalid_action',
                ],
            ], 400);
        }

        $aiRequest = new AIRequest(
            action: $action,
            content: $validated['content'],
            options: $validated['options'] ?? [],
        );

        $provider = $validated['options']['provider'] ?? null;

        try {
            $response = $this->aiManager->generate($aiRequest, $provider);

            return response()->json($response->toArray());

        } catch (RateLimitException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => 'rate_limit_exceeded',
                    'retry_after' => $e->retryAfter,
                ],
            ], 429);

        } catch (AIException $e) {
            Log::error('AI generation failed', [
                'action' => $validated['action'],
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->errorCode ?? 'ai_error',
                ],
            ], 500);

        } catch (\Exception $e) {
            Log::error('Unexpected AI error', [
                'action' => $validated['action'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Wystapil nieoczekiwany blad. Sprobuj ponownie.',
                    'code' => 'unexpected_error',
                ],
            ], 500);
        }
    }

    /**
     * Generuje meta tagi SEO.
     */
    public function generateSEO(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:50000'],
            'fields' => ['sometimes', 'array'],
            'provider' => ['sometimes', 'string'],
        ]);

        $aiRequest = new AIRequest(
            action: AIAction::GENERATE_SEO,
            content: $validated['content'],
            options: [
                'title' => $validated['title'],
            ],
        );

        try {
            $response = $this->aiManager->generate($aiRequest, $validated['provider'] ?? null);

            if (! $response->success) {
                return response()->json($response->toArray(), 500);
            }

            // Probujemy sparsowac JSON z odpowiedzi
            $seoData = $this->parseSEOResponse($response->content);

            return response()->json([
                'success' => true,
                'data' => $seoData,
                'tokens_used' => $response->tokensUsed,
            ]);

        } catch (\Exception $e) {
            Log::error('SEO generation failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Nie udalo sie wygenerowac meta tagow SEO.',
                    'code' => 'seo_generation_failed',
                ],
            ], 500);
        }
    }

    /**
     * Sugeruje tytuly artykulu.
     */
    public function suggestTitles(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'topic' => ['required', 'string', 'max:500'],
            'provider' => ['sometimes', 'string'],
        ]);

        $aiRequest = new AIRequest(
            action: AIAction::SUGGEST_TITLES,
            content: $validated['topic'],
        );

        try {
            $response = $this->aiManager->generate($aiRequest, $validated['provider'] ?? null);

            if (! $response->success) {
                return response()->json($response->toArray(), 500);
            }

            // Parsujemy tytuly (kazdy w nowej linii)
            $titles = array_filter(
                array_map('trim', explode("\n", $response->content))
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'titles' => array_values($titles),
                ],
                'tokens_used' => $response->tokensUsed,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'Nie udalo sie wygenerowac sugestii tytulow.',
                    'code' => 'title_suggestion_failed',
                ],
            ], 500);
        }
    }

    /**
     * Zwraca dostepnych providerow i statystyki.
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'providers' => $this->aiManager->getAvailableProviders(),
                'default_provider' => $this->aiManager->getDefaultProvider(),
                'rate_limit' => [
                    'enabled' => config('ai.rate_limit.enabled', true),
                    'daily_limit' => config('ai.rate_limit.max_requests_per_day', 500),
                    'requests_today' => $this->aiManager->getDailyRequestCount(),
                    'remaining' => $this->aiManager->getRemainingRequests(),
                ],
                'actions' => $this->getAvailableActions(),
            ],
        ]);
    }

    /**
     * Parsuje odpowiedz SEO z JSON.
     *
     * @return array<string, string>
     */
    private function parseSEOResponse(string $content): array
    {
        // Usun potencjalne markdown code blocks
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*/', '', $content);
        $content = trim($content);

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            // Jesli nie jest JSON, zwracamy surowa tresc jako description
            return [
                'meta_title' => '',
                'meta_description' => $content,
                'keywords' => '',
            ];
        }

        return [
            'meta_title' => $data['meta_title'] ?? '',
            'meta_description' => $data['meta_description'] ?? '',
            'keywords' => $data['keywords'] ?? '',
        ];
    }

    /**
     * Zwraca liste dostepnych akcji.
     *
     * @return array<string, array{label: string, description: string, requires_selection: bool}>
     */
    private function getAvailableActions(): array
    {
        $actions = [];

        foreach (AIAction::cases() as $action) {
            $actions[$action->value] = [
                'label' => $action->label(),
                'description' => $action->description(),
                'requires_selection' => $action->requiresSelection(),
            ];
        }

        return $actions;
    }
}
