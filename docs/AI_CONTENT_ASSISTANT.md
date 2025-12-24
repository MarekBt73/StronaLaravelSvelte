# AI Content Assistant - MedVita

## Spis tresci

1. [Przeglad systemu](#1-przeglad-systemu)
2. [Architektura](#2-architektura)
3. [Konfiguracja](#3-konfiguracja)
4. [Funkcje AI](#4-funkcje-ai)
5. [Integracja z Filament](#5-integracja-z-filament)
6. [Style Guide](#6-style-guide)
7. [Prompty systemowe](#7-prompty-systemowe)
8. [API Endpoints](#8-api-endpoints)
9. [Obsluga bledow](#9-obsluga-bledow)
10. [Rozszerzanie o nowe LLM](#10-rozszerzanie-o-nowe-llm)
11. [Plan implementacji](#11-plan-implementacji)

---

## 1. Przeglad systemu

AI Content Assistant to modul wspomagajacy tworzenie tresci w panelu administracyjnym MedVita. Wykorzystuje modele jezykowe (LLM) do generowania, edycji i optymalizacji artykulow blogowych.

### Glowne funkcje

- Generowanie artykulow od zera
- Rozwijanie i poprawianie istniejacego tekstu
- Automatyczne formatowanie HTML/Tailwind
- Generowanie meta tagow SEO
- Sugestie tytulow i slownikow kluczowych
- Dostosowanie tonu i stylu do brandu MedVita

### Wspierane modele LLM

| Provider | Model | Status |
|----------|-------|--------|
| Google | Gemini Flash 2.5 | Aktywny |
| Anthropic | Claude 3.5 Sonnet | Planowany |
| OpenAI | GPT-4o | Planowany |

---

## 2. Architektura

### 2.1 Struktura plikow

```
app/
├── Services/
│   └── AI/
│       ├── Contracts/
│       │   └── AIProviderInterface.php    # Interfejs dla wszystkich LLM
│       ├── Providers/
│       │   ├── GeminiProvider.php         # Google Gemini Flash 2.5
│       │   ├── ClaudeProvider.php         # Anthropic Claude (przyszlosc)
│       │   └── OpenAIProvider.php         # OpenAI GPT (przyszlosc)
│       ├── AIManager.php                  # Factory - wybor providera
│       ├── DTOs/
│       │   ├── AIRequest.php              # Request DTO
│       │   └── AIResponse.php             # Response DTO
│       └── Prompts/
│           ├── ArticlePrompts.php         # Prompty dla artykulow
│           ├── SEOPrompts.php             # Prompty SEO
│           └── StyleGuide.php             # Instrukcje stylizacji
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── AIController.php           # API dla panelu admin
├── Filament/
│   └── Resources/
│       └── ArticleResource/
│           └── Pages/
│               └── EditArticle.php        # Rozszerzony edytor z AI
config/
└── ai.php                                 # Konfiguracja AI
```

### 2.2 Diagram przepływu

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Filament UI   │────►│  AIController   │────►│   AIManager     │
│   (TinyMCE)     │     │  (API endpoint) │     │   (Factory)     │
└─────────────────┘     └─────────────────┘     └────────┬────────┘
                                                         │
                               ┌─────────────────────────┼─────────────────────────┐
                               │                         │                         │
                               ▼                         ▼                         ▼
                        ┌─────────────┐          ┌─────────────┐          ┌─────────────┐
                        │   Gemini    │          │   Claude    │          │   OpenAI    │
                        │  Provider   │          │  Provider   │          │  Provider   │
                        └─────────────┘          └─────────────┘          └─────────────┘
                               │
                               ▼
                        ┌─────────────┐
                        │  StyleGuide │
                        │  + Prompts  │
                        └─────────────┘
```

### 2.3 Interfejs providera

```php
<?php

namespace App\Services\AI\Contracts;

use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\DTOs\AIResponse;

interface AIProviderInterface
{
    /**
     * Generuje tekst na podstawie promptu
     */
    public function generate(AIRequest $request): AIResponse;

    /**
     * Sprawdza dostepnosc API
     */
    public function isAvailable(): bool;

    /**
     * Zwraca nazwe providera
     */
    public function getName(): string;

    /**
     * Zwraca dostepne modele
     */
    public function getModels(): array;
}
```

---

## 3. Konfiguracja

### 3.1 Zmienne srodowiskowe (.env)

```env
# =====================
# AI Content Assistant
# =====================

# Aktywny provider: gemini | claude | openai
AI_PROVIDER=gemini

# Google Gemini
AI_GEMINI_API_KEY=your-gemini-api-key-here
AI_GEMINI_MODEL=gemini-2.0-flash-exp

# Anthropic Claude (przyszlosc)
AI_CLAUDE_API_KEY=
AI_CLAUDE_MODEL=claude-3-5-sonnet-20241022

# OpenAI (przyszlosc)
AI_OPENAI_API_KEY=
AI_OPENAI_MODEL=gpt-4o

# Limity
AI_MAX_TOKENS=4096
AI_TEMPERATURE=0.7
AI_TIMEOUT_SECONDS=60
```

### 3.2 Plik konfiguracyjny (config/ai.php)

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    */
    'default' => env('AI_PROVIDER', 'gemini'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
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
    */
    'rate_limit' => [
        'enabled' => true,
        'max_requests_per_minute' => 20,
        'max_requests_per_day' => 500,
    ],
];
```

---

## 4. Funkcje AI

### 4.1 Lista funkcji

| ID | Funkcja | Opis | Input | Output |
|----|---------|------|-------|--------|
| `generate_article` | Generuj artykul | Tworzy caly artykul od zera | Temat, ton, dlugosc | HTML artykulu |
| `expand_text` | Rozwin tekst | Rozwija zaznaczony fragment | Tekst do rozwinieciu | Rozwiniety tekst |
| `improve_style` | Popraw styl | Korekta gramatyczna i stylistyczna | Tekst | Poprawiony tekst |
| `simplify` | Uprość jezyk | Prostszy jezyk dla pacjentow | Tekst | Uproszczony tekst |
| `format_html` | Formatuj HTML | Dodaje klasy Tailwind, semantyczny HTML | Tekst/HTML | Sformatowany HTML |
| `generate_seo` | Generuj SEO | Meta title, description, keywords | Tytul + tresc | Meta tagi |
| `suggest_titles` | Sugeruj tytuly | 5 propozycji tytulow | Temat/tresc | Lista tytulow |
| `summarize` | Streszczenie | Tworzy lead/excerpt | Tresc | Streszczenie |
| `translate` | Tlumacz | Tlumaczenie PL<->EN | Tekst + jezyk docelowy | Przetlumaczony tekst |

### 4.2 Parametry akcji

```php
<?php

namespace App\Services\AI;

enum AIAction: string
{
    case GENERATE_ARTICLE = 'generate_article';
    case EXPAND_TEXT = 'expand_text';
    case IMPROVE_STYLE = 'improve_style';
    case SIMPLIFY = 'simplify';
    case FORMAT_HTML = 'format_html';
    case GENERATE_SEO = 'generate_seo';
    case SUGGEST_TITLES = 'suggest_titles';
    case SUMMARIZE = 'summarize';
    case TRANSLATE = 'translate';
}
```

### 4.3 Przykladowe uzycie

```php
use App\Services\AI\AIManager;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\AIAction;

$ai = app(AIManager::class);

// Generowanie artykulu
$response = $ai->generate(new AIRequest(
    action: AIAction::GENERATE_ARTICLE,
    content: 'Profilaktyka chorob serca u osob po 40 roku zycia',
    options: [
        'tone' => 'professional_medical',
        'length' => 'medium', // short | medium | long
        'include_cta' => true,
    ]
));

echo $response->content; // HTML artykulu
echo $response->tokensUsed;
echo $response->model;
```

---

## 5. Integracja z Filament

### 5.1 Panel AI w ArticleResource

```
┌─────────────────────────────────────────────────────────────────┐
│  Edycja artykulu                              [Zapisz] [Usun]   │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Tytul: [Profilaktyka chorob serca        ] [✨ Sugeruj tytuly] │
│                                                                 │
│  ┌─ TinyMCE ───────────────────────────────────────────────┐    │
│  │  [B] [I] [U] ... [✨ AI ▼]                               │    │
│  │                   ├─ Rozwin zaznaczenie                  │    │
│  │                   ├─ Popraw styl                         │    │
│  │                   ├─ Uprość jezyk                        │    │
│  │                   ├─ Formatuj HTML/Tailwind              │    │
│  │                   └─ Generuj caly artykul...             │    │
│  │                                                         │    │
│  │  [Tresc artykulu...]                                    │    │
│  │                                                         │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                 │
│  ┌─ AI Content Assistant ──────────────────────────────────┐    │
│  │                                                         │    │
│  │  Provider: [Gemini Flash 2.5 ▼]   Akcja: [Generuj ▼]    │    │
│  │                                                         │    │
│  │  Prompt / Instrukcje:                                   │    │
│  │  ┌─────────────────────────────────────────────────┐    │    │
│  │  │ Napisz artykul o profilaktyce chorob serca.     │    │    │
│  │  │ Uwzglednij: dieta, aktywnosc fizyczna, badania. │    │    │
│  │  │ Grupa docelowa: pacjenci 40+.                   │    │    │
│  │  └─────────────────────────────────────────────────┘    │    │
│  │                                                         │    │
│  │  Ton: [Profesjonalny ▼]    Dlugosc: [Sredni ~800 slow]  │    │
│  │                                                         │    │
│  │  [🚀 Generuj]                                           │    │
│  │                                                         │    │
│  │  ┌─ Wynik ─────────────────────────────────────────┐    │    │
│  │  │ <article class="prose">                         │    │    │
│  │  │   <h2>Zdrowe serce po czterdziestce</h2>        │    │    │
│  │  │   <p class="lead">...</p>                       │    │    │
│  │  │ </article>                                      │    │    │
│  │  │                                                 │    │    │
│  │  │ Tokeny: 1,234 | Model: gemini-2.0-flash         │    │    │
│  │  │                                                 │    │    │
│  │  │ [Wstaw do edytora] [Regeneruj] [Kopiuj] [X]     │    │    │
│  │  └─────────────────────────────────────────────────┘    │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                 │
│  ┌─ SEO ───────────────────────────────────────────────────┐    │
│  │  Meta Title: [________________________] [✨ Generuj AI]  │    │
│  │  Meta Desc:  [________________________] [✨ Generuj AI]  │    │
│  │  Keywords:   [________________________] [✨ Sugeruj AI]  │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 5.2 Komponenty Filament

```php
// Przykladowa implementacja w ArticleResource

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Actions\Action;

Section::make('AI Content Assistant')
    ->icon('heroicon-o-sparkles')
    ->collapsible()
    ->schema([
        Select::make('ai_provider')
            ->label('Provider')
            ->options([
                'gemini' => 'Gemini Flash 2.5',
                'claude' => 'Claude 3.5 Sonnet',
                'openai' => 'GPT-4o',
            ])
            ->default('gemini'),

        Select::make('ai_action')
            ->label('Akcja')
            ->options([
                'generate_article' => 'Generuj artykul',
                'expand_text' => 'Rozwin tekst',
                'improve_style' => 'Popraw styl',
                'format_html' => 'Formatuj HTML',
            ]),

        Textarea::make('ai_prompt')
            ->label('Prompt / Instrukcje')
            ->rows(4)
            ->placeholder('Opisz czego oczekujesz od AI...'),

        Select::make('ai_tone')
            ->label('Ton')
            ->options([
                'professional_medical' => 'Profesjonalny medyczny',
                'friendly' => 'Przyjazny, przystepny',
                'formal' => 'Formalny',
            ])
            ->default('professional_medical'),
    ])
    ->footerActions([
        Action::make('generate')
            ->label('Generuj')
            ->icon('heroicon-o-sparkles')
            ->action(fn () => $this->generateAIContent()),
    ]);
```

---

## 6. Style Guide

### 6.1 Instrukcje stylizacji (wbudowane w prompty)

```php
<?php

namespace App\Services\AI\Prompts;

class StyleGuide
{
    public static function get(): string
    {
        return <<<'PROMPT'
## Instrukcje stylizacji dla MedVita

### Ton i jezyk:
- Profesjonalny, ale przystepny dla pacjentow
- Unikaj zargonu medycznego lub wyjasniaj terminy w nawiasach
- Zwracaj sie per "Panstwo" lub bezosobowo ("warto", "nalezy")
- Empatyczny, wspierajacy ton - bez straszenia
- Unikaj potocznych wyrazen

### Struktura artykulu:
1. **Lead** (1-2 zdania) - podsumowanie calego artykulu
2. **Kluczowe informacje** - bullet points na poczatku (dla LLM/AI search)
3. **Tresc glowna** - sekcje z naglowkami h2/h3
4. **Podsumowanie** - 2-3 zdania
5. **CTA** (opcjonalnie) - zacheta do umowienia wizyty

### Formatowanie HTML z Tailwind CSS:

#### Naglowki:
```html
<h2 class="text-2xl font-bold text-medical-800 mt-8 mb-4">Tytul sekcji</h2>
<h3 class="text-xl font-semibold text-medical-700 mt-6 mb-3">Podtytul</h3>
```

#### Paragrafy:
```html
<p class="text-gray-700 leading-relaxed mb-4">Tresc paragrafu...</p>
<p class="lead text-lg text-gray-600 mb-6">Lead artykulu...</p>
```

#### Listy:
```html
<ul class="list-disc list-inside space-y-2 mb-6 text-gray-700">
  <li>Element listy</li>
</ul>

<ol class="list-decimal list-inside space-y-2 mb-6 text-gray-700">
  <li>Element numerowany</li>
</ol>
```

#### Wyroznienia:
```html
<strong class="font-semibold text-medical-700">Wazny tekst</strong>
<em class="italic">Tekst wyroznoiny</em>
```

#### Callout / Wazne informacje:
```html
<div class="bg-medical-50 border-l-4 border-medical-500 p-4 my-6 rounded-r">
  <p class="font-semibold text-medical-800 mb-1">Wazne</p>
  <p class="text-medical-700">Tresc waznej informacji...</p>
</div>
```

#### Ostrzezenie:
```html
<div class="bg-red-50 border-l-4 border-red-500 p-4 my-6 rounded-r">
  <p class="font-semibold text-red-800 mb-1">Uwaga</p>
  <p class="text-red-700">Tresc ostrzezenia...</p>
</div>
```

#### CTA (Call to Action):
```html
<div class="bg-medical-100 p-6 rounded-lg my-8 text-center">
  <p class="text-lg text-medical-800 mb-4">Masz pytania dotyczace swojego zdrowia?</p>
  <a href="/kontakt" class="inline-block bg-medical-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-medical-700 transition">
    Umow wizyte
  </a>
</div>
```

### SEO:
- Meta title: max 60 znakow, slowo kluczowe na poczatku
- Meta description: 150-160 znakow, zachecajacy, z CTA
- Uzywaj nagłowkow hierarchicznie (h2 > h3 > h4)
- Dodawaj alt do obrazow (jesli wspominasz o obrazach)

### Czego unikac:
- Zbyt dlugich paragrafow (max 4-5 zdan)
- Scian tekstu bez formatowania
- Zbyt wielu wykrzyknikow
- Pustych obietnic ("najlepszy", "jedyny")
- Informacji medycznych bez zastrzezenia o konsultacji z lekarzem
PROMPT;
    }
}
```

### 6.2 Paleta kolorow Tailwind (medical)

```javascript
// tailwind.config.js - dla referencji AI
module.exports = {
  theme: {
    extend: {
      colors: {
        medical: {
          50:  '#f0f9f4',
          100: '#dcf2e6',
          200: '#bbe5cf',
          300: '#8dd3b0',
          400: '#58b888',
          500: '#35a06d',
          600: '#258156',
          700: '#1f6746',
          800: '#1c523a',
          900: '#184430',
        }
      }
    }
  }
}
```

---

## 7. Prompty systemowe

### 7.1 Prompt bazowy

```php
<?php

namespace App\Services\AI\Prompts;

class ArticlePrompts
{
    public static function getSystemPrompt(): string
    {
        $styleGuide = StyleGuide::get();

        return <<<PROMPT
Jestes asystentem AI pomagajacym tworzyc tresci dla strony internetowej kliniki medycznej MedVita.

{$styleGuide}

## Zasady ogolne:
1. Zawsze generuj poprawny HTML z klasami Tailwind
2. Pisz po polsku, poprawna polszczyzna
3. Badz dokladny merytorycznie - to tresci medyczne
4. Dodawaj zastrzezenia o koniecznosci konsultacji z lekarzem
5. Nie wymyslaj statystyk - jesli nie znasz, napisz ogolnie
6. Formatuj tekst czytelnie - uzywaj list, nagłowkow, calloutow

Odpowiadaj TYLKO wygenerowana trescia, bez dodatkowych komentarzy.
PROMPT;
    }

    public static function getGenerateArticlePrompt(string $topic, array $options = []): string
    {
        $tone = $options['tone'] ?? 'professional_medical';
        $length = $options['length'] ?? 'medium';
        $includeCta = $options['include_cta'] ?? true;

        $lengthGuide = match($length) {
            'short' => '400-600 slow',
            'medium' => '800-1000 slow',
            'long' => '1200-1500 slow',
            default => '800-1000 slow',
        };

        $toneGuide = match($tone) {
            'professional_medical' => 'profesjonalny medyczny, ale zrozumialy dla pacjentow',
            'friendly' => 'przyjazny i przystepny, jak rozmowa z zaufanym lekarzem',
            'formal' => 'formalny i rzeczowy',
            default => 'profesjonalny medyczny',
        };

        $ctaSection = $includeCta
            ? 'Dodaj na koncu sekcje CTA zachecajaca do umowienia wizyty.'
            : 'Nie dodawaj sekcji CTA.';

        return <<<PROMPT
Napisz artykul na temat: {$topic}

Wymagania:
- Dlugosc: {$lengthGuide}
- Ton: {$toneGuide}
- Struktura: lead, kluczowe punkty, rozwinecie, podsumowanie
- {$ctaSection}

Uzyj formatowania HTML z klasami Tailwind zgodnie z wytycznymi.
PROMPT;
    }

    public static function getExpandTextPrompt(string $text): string
    {
        return <<<PROMPT
Rozwin ponizszy tekst, dodajac wiecej szczegolow i przykladow.
Zachowaj oryginalny ton i styl. Uzyj formatowania HTML/Tailwind.

Tekst do rozwinieciu:
{$text}
PROMPT;
    }

    public static function getImproveStylePrompt(string $text): string
    {
        return <<<PROMPT
Popraw styl i gramatyke ponizszego tekstu.
Zachowaj sens i strukture. Popraw bledy jezykowe, ulepsz plynnosc.
Zachowaj formatowanie HTML.

Tekst do poprawy:
{$text}
PROMPT;
    }

    public static function getSimplifyPrompt(string $text): string
    {
        return <<<PROMPT
Uprość ponizszy tekst tak, aby byl zrozumialy dla przecietnego pacjenta.
Zamien zargon medyczny na proste wyjasnienia.
Zachowaj formatowanie HTML.

Tekst do uproszczenia:
{$text}
PROMPT;
    }

    public static function getFormatHtmlPrompt(string $text): string
    {
        return <<<PROMPT
Sformatuj ponizszy tekst jako poprawny HTML z klasami Tailwind CSS.
Dodaj odpowiednie naglowki, listy, wyroznienia, callout-y.
Uzyj klas z palety 'medical' dla kolorow.

Tekst do formatowania:
{$text}
PROMPT;
    }
}
```

### 7.2 Prompty SEO

```php
<?php

namespace App\Services\AI\Prompts;

class SEOPrompts
{
    public static function getMetaTitlePrompt(string $articleTitle, string $content): string
    {
        return <<<PROMPT
Wygeneruj meta title dla artykulu.

Tytul artykulu: {$articleTitle}

Poczatek tresci:
{$content}

Wymagania:
- Max 60 znakow
- Slowo kluczowe na poczatku
- Zachecajacy do klikniecia
- Bez nazwy firmy (dodamy automatycznie)

Odpowiedz TYLKO meta title, bez dodatkowego tekstu.
PROMPT;
    }

    public static function getMetaDescriptionPrompt(string $articleTitle, string $content): string
    {
        return <<<PROMPT
Wygeneruj meta description dla artykulu.

Tytul: {$articleTitle}

Poczatek tresci:
{$content}

Wymagania:
- 150-160 znakow
- Streszczenie zawartosci
- Zacheta do przeczytania
- Moze zawierac CTA

Odpowiedz TYLKO meta description, bez dodatkowego tekstu.
PROMPT;
    }

    public static function getSuggestKeywordsPrompt(string $articleTitle, string $content): string
    {
        return <<<PROMPT
Zaproponuj slowa kluczowe (keywords) dla artykulu.

Tytul: {$articleTitle}

Poczatek tresci:
{$content}

Wymagania:
- 5-8 slow kluczowych
- Po polsku
- Mix: glowne slowo + long-tail
- Oddzielone przecinkami

Odpowiedz TYLKO lista slow kluczowych, bez dodatkowego tekstu.
PROMPT;
    }

    public static function getSuggestTitlesPrompt(string $topic): string
    {
        return <<<PROMPT
Zaproponuj 5 tytulow artykulu na temat: {$topic}

Wymagania:
- Roznorodne style (pytanie, how-to, lista, etc.)
- SEO-friendly (slowo kluczowe blisko poczatku)
- Zachecajace do klikniecia
- Max 70 znakow kazdy

Odpowiedz w formacie:
1. Tytul pierwszy
2. Tytul drugi
...
PROMPT;
    }
}
```

---

## 8. API Endpoints

### 8.1 Endpoint generowania

```
POST /admin/api/ai/generate
```

**Request:**
```json
{
  "action": "generate_article",
  "content": "Profilaktyka chorob serca u osob po 40 roku zycia",
  "options": {
    "provider": "gemini",
    "tone": "professional_medical",
    "length": "medium",
    "include_cta": true
  }
}
```

**Response (sukces):**
```json
{
  "success": true,
  "data": {
    "content": "<article class=\"prose\">...</article>",
    "tokens_used": 1234,
    "model": "gemini-2.0-flash-exp",
    "provider": "gemini"
  }
}
```

**Response (blad):**
```json
{
  "success": false,
  "error": {
    "code": "rate_limit_exceeded",
    "message": "Przekroczono limit zapytan. Sprobuj ponownie za minute."
  }
}
```

### 8.2 Endpoint SEO

```
POST /admin/api/ai/seo
```

**Request:**
```json
{
  "action": "generate_meta",
  "title": "Profilaktyka chorob serca",
  "content": "Pierwsze 500 znakow artykulu...",
  "fields": ["meta_title", "meta_description", "keywords"]
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "meta_title": "Profilaktyka chorob serca - 7 skutecznych sposobow",
    "meta_description": "Dowiedz sie, jak chronic swoje serce. Poznaj 7 sprawdzonych metod profilaktyki kardiologicznej zalecanych przez specjalistow.",
    "keywords": "profilaktyka serca, choroby serca, zdrowe serce, kardiologia, badania serca"
  }
}
```

---

## 9. Obsluga bledow

### 9.1 Kody bledow

| Kod | HTTP | Opis |
|-----|------|------|
| `invalid_request` | 400 | Nieprawidlowe parametry zadania |
| `unauthorized` | 401 | Brak autoryzacji |
| `rate_limit_exceeded` | 429 | Przekroczono limit zapytan |
| `provider_error` | 502 | Blad po stronie providera AI |
| `timeout` | 504 | Przekroczono czas oczekiwania |
| `content_filtered` | 422 | Tresc zablokowana przez filtr bezpieczenstwa |

### 9.2 Obsluga w kodzie

```php
<?php

namespace App\Services\AI;

use App\Services\AI\Exceptions\AIException;
use App\Services\AI\Exceptions\RateLimitException;
use App\Services\AI\Exceptions\ProviderException;

try {
    $response = $ai->generate($request);
} catch (RateLimitException $e) {
    // Pokaz komunikat o limicie
    return back()->with('error', 'Przekroczono limit zapytan. Sprobuj za minute.');
} catch (ProviderException $e) {
    // Log bledu, pokaz ogolny komunikat
    Log::error('AI Provider error', ['exception' => $e]);
    return back()->with('error', 'Usluga AI chwilowo niedostepna.');
} catch (AIException $e) {
    return back()->with('error', $e->getMessage());
}
```

---

## 10. Rozszerzanie o nowe LLM

### 10.1 Dodawanie nowego providera

1. Utworz klase implementujaca `AIProviderInterface`:

```php
<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\DTOs\AIRequest;
use App\Services\AI\DTOs\AIResponse;

class NewProvider implements AIProviderInterface
{
    public function __construct(
        private string $apiKey,
        private string $model,
        private string $baseUrl,
    ) {}

    public function generate(AIRequest $request): AIResponse
    {
        // Implementacja wywolania API
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    public function getName(): string
    {
        return 'New Provider';
    }

    public function getModels(): array
    {
        return ['model-1', 'model-2'];
    }
}
```

2. Dodaj konfiguracje w `config/ai.php`:

```php
'providers' => [
    // ... existing providers
    'new_provider' => [
        'class' => \App\Services\AI\Providers\NewProvider::class,
        'api_key' => env('AI_NEW_PROVIDER_API_KEY'),
        'model' => env('AI_NEW_PROVIDER_MODEL', 'default-model'),
        'base_url' => 'https://api.newprovider.com/v1',
    ],
],
```

3. Dodaj zmienne do `.env`:

```env
AI_NEW_PROVIDER_API_KEY=your-key
AI_NEW_PROVIDER_MODEL=model-name
```

---

## 11. Plan implementacji

### Faza 1: Core (P0)

| # | Zadanie | Plik | Status |
|---|---------|------|--------|
| 1.1 | Konfiguracja config/ai.php | `config/ai.php` | ⏳ |
| 1.2 | AIProviderInterface | `app/Services/AI/Contracts/AIProviderInterface.php` | ⏳ |
| 1.3 | DTOs (AIRequest, AIResponse) | `app/Services/AI/DTOs/` | ⏳ |
| 1.4 | GeminiProvider | `app/Services/AI/Providers/GeminiProvider.php` | ⏳ |
| 1.5 | AIManager (factory) | `app/Services/AI/AIManager.php` | ⏳ |
| 1.6 | StyleGuide | `app/Services/AI/Prompts/StyleGuide.php` | ⏳ |
| 1.7 | ArticlePrompts | `app/Services/AI/Prompts/ArticlePrompts.php` | ⏳ |
| 1.8 | SEOPrompts | `app/Services/AI/Prompts/SEOPrompts.php` | ⏳ |

### Faza 2: API (P0)

| # | Zadanie | Plik | Status |
|---|---------|------|--------|
| 2.1 | AIController | `app/Http/Controllers/Admin/AIController.php` | ⏳ |
| 2.2 | Routes (admin API) | `routes/web.php` | ⏳ |
| 2.3 | Request validation | `app/Http/Requests/AI/GenerateRequest.php` | ⏳ |
| 2.4 | Exceptions | `app/Services/AI/Exceptions/` | ⏳ |

### Faza 3: Filament Integration (P1)

| # | Zadanie | Plik | Status |
|---|---------|------|--------|
| 3.1 | AI Section w ArticleResource | `app/Filament/Resources/ArticleResource.php` | ⏳ |
| 3.2 | Livewire component dla AI panelu | `app/Livewire/AIPanel.php` | ⏳ |
| 3.3 | JavaScript dla TinyMCE button | `resources/js/tinymce-ai-plugin.js` | ⏳ |
| 3.4 | Stylowanie panelu AI | `resources/css/ai-panel.css` | ⏳ |

### Faza 4: Polish & Testing (P2)

| # | Zadanie | Status |
|---|---------|--------|
| 4.1 | Rate limiting | ⏳ |
| 4.2 | Error handling UI | ⏳ |
| 4.3 | Loading states | ⏳ |
| 4.4 | Feature tests | ⏳ |
| 4.5 | Dokumentacja uzytkownika | ⏳ |

---

## Changelog

| Data | Wersja | Opis |
|------|--------|------|
| 2024-12-24 | 1.0 | Pierwsza wersja specyfikacji |

---

**Autor:** Claude Code
**Projekt:** MedVita - AI Content Assistant
