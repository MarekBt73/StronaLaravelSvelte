<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | Domyslny provider AI uzywany do generowania tresci.
    | Dostepne opcje: gemini, claude, openai
    |
    */
    'default' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Konfiguracja poszczegolnych providerow AI.
    | Kazdy provider musi implementowac AIProviderInterface.
    |
    */
    'providers' => [
        'gemini' => [
            'class' => \App\Services\AI\Providers\GeminiProvider::class,
            'api_key' => env('AI_GEMINI_API_KEY'),
            'model' => env('AI_GEMINI_MODEL', 'gemini-2.0-flash-exp'),
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
        ],
        'claude' => [
            'class' => \App\Services\AI\Providers\ClaudeProvider::class,
            'api_key' => env('AI_CLAUDE_API_KEY'),
            'model' => env('AI_CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),
            'base_url' => 'https://api.anthropic.com/v1',
        ],
        'openai' => [
            'class' => \App\Services\AI\Providers\OpenAIProvider::class,
            'api_key' => env('AI_OPENAI_API_KEY'),
            'model' => env('AI_OPENAI_MODEL', 'gpt-4o'),
            'base_url' => 'https://api.openai.com/v1',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Generation Settings
    |--------------------------------------------------------------------------
    |
    | Domyslne ustawienia generowania tresci.
    |
    */
    'settings' => [
        'max_tokens' => (int) env('AI_MAX_TOKENS', 4096),
        'temperature' => (float) env('AI_TEMPERATURE', 0.7),
        'timeout' => (int) env('AI_TIMEOUT_SECONDS', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Limity zapytan do API AI (kontrola kosztow).
    |
    */
    'rate_limit' => [
        'enabled' => (bool) env('AI_RATE_LIMIT_ENABLED', true),
        'max_requests_per_minute' => (int) env('AI_RATE_LIMIT_PER_MINUTE', 20),
        'max_requests_per_day' => (int) env('AI_RATE_LIMIT_PER_DAY', 500),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Actions
    |--------------------------------------------------------------------------
    |
    | Dostepne akcje AI dla tresci.
    |
    */
    'actions' => [
        'generate_article' => [
            'label' => 'Generuj artykul',
            'description' => 'Tworzy caly artykul od zera na podstawie tematu',
        ],
        'expand_text' => [
            'label' => 'Rozwin tekst',
            'description' => 'Rozwija zaznaczony fragment o wiecej szczegolow',
        ],
        'improve_style' => [
            'label' => 'Popraw styl',
            'description' => 'Korekta gramatyczna i stylistyczna',
        ],
        'simplify' => [
            'label' => 'Uprosc jezyk',
            'description' => 'Prostszy jezyk dla pacjentow',
        ],
        'format_html' => [
            'label' => 'Formatuj HTML',
            'description' => 'Dodaje klasy Tailwind i semantyczny HTML',
        ],
        'generate_seo' => [
            'label' => 'Generuj SEO',
            'description' => 'Tworzy meta title, description i keywords',
        ],
        'suggest_titles' => [
            'label' => 'Sugeruj tytuly',
            'description' => 'Proponuje 5 wariantow tytulu',
        ],
        'summarize' => [
            'label' => 'Streszczenie',
            'description' => 'Tworzy krotkie podsumowanie / lead',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tone Options
    |--------------------------------------------------------------------------
    |
    | Dostepne opcje tonu dla generowanych tresci.
    |
    */
    'tones' => [
        'professional_medical' => 'Profesjonalny medyczny',
        'friendly' => 'Przyjazny i przystepny',
        'formal' => 'Formalny',
        'educational' => 'Edukacyjny',
    ],

    /*
    |--------------------------------------------------------------------------
    | Length Options
    |--------------------------------------------------------------------------
    |
    | Dostepne opcje dlugosci dla generowanych artykulow.
    |
    */
    'lengths' => [
        'short' => [
            'label' => 'Krotki (~500 slow)',
            'words' => '400-600',
        ],
        'medium' => [
            'label' => 'Sredni (~800 slow)',
            'words' => '700-900',
        ],
        'long' => [
            'label' => 'Dlugi (~1200 slow)',
            'words' => '1100-1300',
        ],
    ],
];
