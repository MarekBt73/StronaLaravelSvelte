<?php

declare(strict_types=1);

namespace App\Services\AI\Prompts;

class SEOPrompts
{
    /**
     * Prompt do generowania meta title.
     */
    public static function getMetaTitlePrompt(string $articleTitle, string $content): string
    {
        $contentPreview = mb_substr(strip_tags($content), 0, 500);

        return <<<PROMPT
Wygeneruj meta title dla artykulu medycznego.

Tytul artykulu: {$articleTitle}

Poczatek tresci:
{$contentPreview}

Wymagania:
- Max 60 znakow (to bardzo wazne!)
- Slowo kluczowe na poczatku
- Zachecajacy do klikniecia
- Bez nazwy firmy (dodamy automatycznie " | MedVita")
- Jezyk polski

Odpowiedz TYLKO meta title, bez dodatkowego tekstu, cudzyslowow czy komentarzy.
PROMPT;
    }

    /**
     * Prompt do generowania meta description.
     */
    public static function getMetaDescriptionPrompt(string $articleTitle, string $content): string
    {
        $contentPreview = mb_substr(strip_tags($content), 0, 500);

        return <<<PROMPT
Wygeneruj meta description dla artykulu medycznego.

Tytul: {$articleTitle}

Poczatek tresci:
{$contentPreview}

Wymagania:
- Dokladnie 150-160 znakow (to bardzo wazne!)
- Streszczenie zawartosci artykulu
- Zacheta do przeczytania
- Moze zawierac CTA (np. "Dowiedz sie wiecej")
- Jezyk polski

Odpowiedz TYLKO meta description, bez dodatkowego tekstu, cudzyslowow czy komentarzy.
PROMPT;
    }

    /**
     * Prompt do sugerowania slow kluczowych.
     */
    public static function getKeywordsPrompt(string $articleTitle, string $content): string
    {
        $contentPreview = mb_substr(strip_tags($content), 0, 800);

        return <<<PROMPT
Zaproponuj slowa kluczowe (keywords) dla artykulu medycznego.

Tytul: {$articleTitle}

Poczatek tresci:
{$contentPreview}

Wymagania:
- 5-8 slow/fraz kluczowych
- Po polsku
- Mix: glowne slowo kluczowe + frazy long-tail
- Oddzielone przecinkami
- Zwiazane z tematyka medyczna

Odpowiedz TYLKO lista slow kluczowych oddzielonych przecinkami, bez dodatkowego tekstu.
Przyklad formatu: profilaktyka serca, choroby ukladu krazenia, zdrowe serce, badania kardiologiczne
PROMPT;
    }

    /**
     * Prompt do sugerowania tytulow.
     */
    public static function getSuggestTitlesPrompt(string $topic): string
    {
        return <<<PROMPT
Zaproponuj 5 tytulow artykulu medycznego na temat: {$topic}

Wymagania:
- Roznorodne style (pytanie, how-to, lista, stwierdzenie, problem-rozwiazanie)
- SEO-friendly (slowo kluczowe blisko poczatku)
- Zachecajace do klikniecia
- Max 70 znakow kazdy
- Po polsku
- Profesjonalny ton medyczny

Odpowiedz w formacie (kazdy tytul w nowej linii, bez numeracji):
Tytul pierwszy
Tytul drugi
Tytul trzeci
Tytul czwarty
Tytul piaty
PROMPT;
    }

    /**
     * Prompt do generowania pelnego zestawu SEO.
     */
    public static function getFullSEOPrompt(string $articleTitle, string $content): string
    {
        $contentPreview = mb_substr(strip_tags($content), 0, 800);

        return <<<PROMPT
Wygeneruj kompletny zestaw meta tagow SEO dla artykulu medycznego.

Tytul: {$articleTitle}

Tresc artykulu:
{$contentPreview}

Wygeneruj w formacie JSON (bez dodatkowego tekstu, tylko JSON):
{
  "meta_title": "tytul max 60 znakow",
  "meta_description": "opis 150-160 znakow",
  "keywords": "slowo1, slowo2, slowo3, slowo4, slowo5"
}

Wymagania:
- meta_title: max 60 znakow, slowo kluczowe na poczatku, bez nazwy firmy
- meta_description: dokladnie 150-160 znakow, zachecajacy
- keywords: 5-8 slow kluczowych po polsku, oddzielonych przecinkami

Odpowiedz TYLKO poprawnym JSON, bez komentarzy czy dodatkowego tekstu.
PROMPT;
    }
}
