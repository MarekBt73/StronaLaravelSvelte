<?php

declare(strict_types=1);

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\DTOs\AIResponse;
use App\Services\AI\Exceptions\ProviderException;
use App\Services\AI\Exceptions\RateLimitException;
use App\Services\AI\Prompts\ArticlePrompts;
use App\Services\AI\Prompts\SEOPrompts;
use App\Services\AI\Prompts\StyleGuide;
use App\Services\AI\AIAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $timeout;
    private int $maxTokens;
    private float $temperature;

    public function __construct()
    {
        $config = config('ai.providers.gemini');
        $settings = config('ai.settings');

        $this->apiKey = $config['api_key'] ?? '';
        $this->model = $config['model'] ?? 'gemini-2.0-flash-exp';
        $this->baseUrl = $config['base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta';
        $this->timeout = $settings['timeout'] ?? 60;
        $this->maxTokens = $settings['max_tokens'] ?? 4096;
        $this->temperature = $settings['temperature'] ?? 0.7;
    }

    public function generate(AIRequest $request): AIResponse
    {
        if (! $this->isAvailable()) {
            return AIResponse::error('Klucz API Gemini nie jest skonfigurowany.', 'missing_api_key');
        }

        try {
            $prompt = $this->buildPrompt($request);
            $systemPrompt = $request->systemPrompt ?? ArticlePrompts::getSystemPrompt();

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->getEndpoint(), [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $systemPrompt . "\n\n" . $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => $this->temperature,
                        'maxOutputTokens' => $this->maxTokens,
                        'topP' => 0.95,
                        'topK' => 40,
                    ],
                    'safetySettings' => [
                        [
                            'category' => 'HARM_CATEGORY_HARASSMENT',
                            'threshold' => 'BLOCK_ONLY_HIGH',
                        ],
                        [
                            'category' => 'HARM_CATEGORY_HATE_SPEECH',
                            'threshold' => 'BLOCK_ONLY_HIGH',
                        ],
                        [
                            'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                            'threshold' => 'BLOCK_ONLY_HIGH',
                        ],
                        [
                            'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                            'threshold' => 'BLOCK_ONLY_HIGH',
                        ],
                    ],
                ]);

            if ($response->status() === 429) {
                throw new RateLimitException();
            }

            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'Nieznany blad API Gemini';

                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $errorBody,
                ]);

                throw new ProviderException($errorMessage, 'gemini_error', json_encode($errorBody));
            }

            $data = $response->json();
            $content = $this->extractContent($data);
            $tokenInfo = $this->extractTokenInfo($data);

            return AIResponse::success(
                content: $content,
                model: $this->model,
                provider: $this->getIdentifier(),
                tokensUsed: $tokenInfo['total'],
                promptTokens: $tokenInfo['prompt'],
                completionTokens: $tokenInfo['completion'],
            );

        } catch (RateLimitException $e) {
            throw $e;
        } catch (ProviderException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Gemini Provider exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AIResponse::error(
                'Wystapil blad podczas komunikacji z Gemini: ' . $e->getMessage(),
                'connection_error'
            );
        }
    }

    public function isAvailable(): bool
    {
        return ! empty($this->apiKey);
    }

    public function getName(): string
    {
        return 'Google Gemini';
    }

    public function getIdentifier(): string
    {
        return 'gemini';
    }

    public function getModels(): array
    {
        return [
            'gemini-2.0-flash-exp' => 'Gemini 2.0 Flash (Experimental)',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
        ];
    }

    public function getCurrentModel(): string
    {
        return $this->model;
    }

    /**
     * Buduje endpoint URL z kluczem API.
     */
    private function getEndpoint(): string
    {
        return sprintf(
            '%s/models/%s:generateContent?key=%s',
            $this->baseUrl,
            $this->model,
            $this->apiKey
        );
    }

    /**
     * Buduje prompt na podstawie akcji.
     */
    private function buildPrompt(AIRequest $request): string
    {
        return match ($request->action) {
            AIAction::GENERATE_ARTICLE => ArticlePrompts::getGenerateArticlePrompt(
                $request->content,
                [
                    'tone' => $request->getTone(),
                    'length' => $request->getLength(),
                    'include_cta' => $request->shouldIncludeCta(),
                ]
            ),
            AIAction::EXPAND_TEXT => ArticlePrompts::getExpandTextPrompt($request->content),
            AIAction::IMPROVE_STYLE => ArticlePrompts::getImproveStylePrompt($request->content),
            AIAction::SIMPLIFY => ArticlePrompts::getSimplifyPrompt($request->content),
            AIAction::FORMAT_HTML => ArticlePrompts::getFormatHtmlPrompt($request->content),
            AIAction::GENERATE_SEO => SEOPrompts::getFullSEOPrompt(
                $request->getOption('title', ''),
                $request->content
            ),
            AIAction::SUGGEST_TITLES => SEOPrompts::getSuggestTitlesPrompt($request->content),
            AIAction::SUMMARIZE => ArticlePrompts::getSummarizePrompt($request->content),
            AIAction::TRANSLATE => ArticlePrompts::getTranslatePrompt(
                $request->content,
                $request->getTargetLanguage()
            ),
        };
    }

    /**
     * Wyciaga tresc z odpowiedzi Gemini.
     */
    private function extractContent(array $data): string
    {
        $candidates = $data['candidates'] ?? [];

        if (empty($candidates)) {
            return '';
        }

        $content = $candidates[0]['content']['parts'][0]['text'] ?? '';

        return trim($content);
    }

    /**
     * Wyciaga informacje o tokenach z odpowiedzi.
     *
     * @return array{total: int|null, prompt: int|null, completion: int|null}
     */
    private function extractTokenInfo(array $data): array
    {
        $usage = $data['usageMetadata'] ?? [];

        return [
            'prompt' => $usage['promptTokenCount'] ?? null,
            'completion' => $usage['candidatesTokenCount'] ?? null,
            'total' => isset($usage['totalTokenCount']) ? (int) $usage['totalTokenCount'] : null,
        ];
    }
}
