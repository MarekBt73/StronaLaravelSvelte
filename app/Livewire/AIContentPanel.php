<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Services\AI\AIAction;
use App\Services\AI\AIManager;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\Exceptions\AIException;
use Filament\Notifications\Notification;
use Livewire\Component;

class AIContentPanel extends Component
{
    public string $prompt = '';
    public string $action = 'generate_article';
    public string $provider = 'gemini';
    public string $tone = 'professional_medical';
    public string $length = 'medium';
    public bool $includeCta = true;

    public string $result = '';
    public bool $isLoading = false;
    public ?int $tokensUsed = null;

    public ?string $articleTitle = null;
    public ?string $articleContent = null;

    protected $listeners = [
        'setArticleContext' => 'setContext',
    ];

    public function mount(?string $title = null, ?string $content = null): void
    {
        $this->articleTitle = $title;
        $this->articleContent = $content;
        $this->provider = config('ai.default', 'gemini');
    }

    public function setContext(string $title, string $content): void
    {
        $this->articleTitle = $title;
        $this->articleContent = $content;
    }

    public function generate(): void
    {
        if (empty($this->prompt) && $this->action === 'generate_article') {
            Notification::make()
                ->title('Wprowadz temat artykulu')
                ->warning()
                ->send();
            return;
        }

        $this->isLoading = true;
        $this->result = '';
        $this->tokensUsed = null;

        try {
            $aiManager = app(AIManager::class);

            $content = $this->action === 'generate_article'
                ? $this->prompt
                : ($this->articleContent ?? $this->prompt);

            $request = new AIRequest(
                action: AIAction::from($this->action),
                content: $content,
                options: [
                    'tone' => $this->tone,
                    'length' => $this->length,
                    'include_cta' => $this->includeCta,
                    'title' => $this->articleTitle,
                ],
            );

            $response = $aiManager->generate($request, $this->provider);

            if ($response->success) {
                $this->result = $response->content;
                $this->tokensUsed = $response->tokensUsed;

                Notification::make()
                    ->title('Tresc wygenerowana pomyslnie')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Blad generowania')
                    ->body($response->error ?? 'Nieznany blad')
                    ->danger()
                    ->send();
            }

        } catch (AIException $e) {
            Notification::make()
                ->title('Blad AI')
                ->body($e->getMessage())
                ->danger()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Wystapil nieoczekiwany blad')
                ->body('Sprobuj ponownie pozniej.')
                ->danger()
                ->send();
        } finally {
            $this->isLoading = false;
        }
    }

    public function generateSEO(): void
    {
        if (empty($this->articleTitle) || empty($this->articleContent)) {
            Notification::make()
                ->title('Najpierw wprowadz tytul i tresc artykulu')
                ->warning()
                ->send();
            return;
        }

        $this->isLoading = true;

        try {
            $aiManager = app(AIManager::class);

            $request = new AIRequest(
                action: AIAction::GENERATE_SEO,
                content: $this->articleContent,
                options: [
                    'title' => $this->articleTitle,
                ],
            );

            $response = $aiManager->generate($request, $this->provider);

            if ($response->success) {
                // Parsuj JSON z odpowiedzi
                $content = preg_replace('/```json\s*/', '', $response->content);
                $content = preg_replace('/```\s*/', '', $content);
                $seoData = json_decode(trim($content), true);

                if ($seoData) {
                    $this->dispatch('seoGenerated', $seoData);

                    Notification::make()
                        ->title('Meta tagi SEO wygenerowane')
                        ->success()
                        ->send();
                }
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Blad generowania SEO')
                ->danger()
                ->send();
        } finally {
            $this->isLoading = false;
        }
    }

    public function suggestTitles(): void
    {
        if (empty($this->prompt)) {
            Notification::make()
                ->title('Wprowadz temat artykulu')
                ->warning()
                ->send();
            return;
        }

        $this->isLoading = true;

        try {
            $aiManager = app(AIManager::class);

            $request = new AIRequest(
                action: AIAction::SUGGEST_TITLES,
                content: $this->prompt,
            );

            $response = $aiManager->generate($request, $this->provider);

            if ($response->success) {
                $this->result = $response->content;
                $this->tokensUsed = $response->tokensUsed;

                Notification::make()
                    ->title('Sugestie tytulow wygenerowane')
                    ->success()
                    ->send();
            }

        } catch (\Exception $e) {
            Notification::make()
                ->title('Blad generowania sugestii')
                ->danger()
                ->send();
        } finally {
            $this->isLoading = false;
        }
    }

    public function insertToEditor(): void
    {
        if (! empty($this->result)) {
            $this->dispatch('insertContent', $this->result);

            Notification::make()
                ->title('Tresc wstawiona do edytora')
                ->success()
                ->send();
        }
    }

    public function clearResult(): void
    {
        $this->result = '';
        $this->tokensUsed = null;
    }

    public function getAvailableActions(): array
    {
        return [
            'generate_article' => 'Generuj artykul',
            'expand_text' => 'Rozwin tekst',
            'improve_style' => 'Popraw styl',
            'simplify' => 'Uprosc jezyk',
            'format_html' => 'Formatuj HTML',
            'summarize' => 'Streszczenie',
        ];
    }

    public function getAvailableTones(): array
    {
        return config('ai.tones', [
            'professional_medical' => 'Profesjonalny medyczny',
            'friendly' => 'Przyjazny i przystepny',
            'formal' => 'Formalny',
            'educational' => 'Edukacyjny',
        ]);
    }

    public function getAvailableLengths(): array
    {
        return [
            'short' => 'Krotki (~500 slow)',
            'medium' => 'Sredni (~800 slow)',
            'long' => 'Dlugi (~1200 slow)',
        ];
    }

    public function getAvailableProviders(): array
    {
        $aiManager = app(AIManager::class);
        $providers = $aiManager->getAvailableProviders();

        $options = [];
        foreach ($providers as $key => $provider) {
            if ($provider['available']) {
                $options[$key] = $provider['name'] . ' (' . $provider['model'] . ')';
            }
        }

        return $options;
    }

    public function render()
    {
        return view('livewire.ai-content-panel');
    }
}
