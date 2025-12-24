<?php

declare(strict_types=1);

namespace App\Services\AI\Prompts;

class ImagePrompts
{
    /**
     * Prompt do generowania opisu ALT i tagow dla obrazu.
     */
    public static function getDescribeImagePrompt(?string $context = null): string
    {
        $contextInfo = $context ? "\n\nKontekst uzycia obrazu: {$context}" : '';

        return <<<PROMPT
Przeanalizuj ten obraz i wygeneruj metadane dla strony medycznej.
{$contextInfo}

Wygeneruj odpowiedz w formacie JSON (bez dodatkowego tekstu, tylko JSON):
{
  "alt_text": "krotki, opisowy tekst ALT dla osob niewidomych (max 125 znakow)",
  "description": "dluzszy opis zawartosci obrazu (1-2 zdania)",
  "tags": "tag1, tag2, tag3, tag4, tag5"
}

WYMAGANIA:
1. alt_text:
   - Maksymalnie 125 znakow
   - Opisowy, informacyjny
   - Po polsku
   - Nie zaczynaj od "Obraz przedstawia..." - od razu opisz zawartosc
   - Jesli na zdjeciu sa osoby, opisz czynnosc, nie wyglad

2. description:
   - 1-2 pelne zdania
   - Bardziej szczegolowy opis niz alt_text
   - Po polsku

3. tags:
   - 4-6 tagow oddzielonych przecinkami
   - Po polsku
   - Zwiazane z tematyka medyczna jezeli to mozliwe
   - Przydatne do kategoryzacji i wyszukiwania

Odpowiedz TYLKO poprawnym JSON, bez komentarzy czy dodatkowego tekstu.
PROMPT;
    }

    /**
     * Prompt do generowania tylko tekstu ALT.
     */
    public static function getAltTextOnlyPrompt(?string $context = null): string
    {
        $contextInfo = $context ? " Kontekst: {$context}" : '';

        return <<<PROMPT
Wygeneruj tekst alternatywny (ALT) dla tego obrazu.{$contextInfo}

WYMAGANIA:
- Maksymalnie 125 znakow
- Opisowy i informacyjny dla osob niewidomych
- Po polsku
- Nie zaczynaj od "Obraz przedstawia..." - od razu opisz zawartosc
- Jesli na zdjeciu sa osoby, opisz czynnosc, nie wyglad

Odpowiedz TYLKO tekstem ALT, bez cudzyslowow, komentarzy czy dodatkowego tekstu.
PROMPT;
    }

    /**
     * Prompt do generowania tagow.
     */
    public static function getTagsOnlyPrompt(?string $context = null): string
    {
        $contextInfo = $context ? " Kontekst: {$context}" : '';

        return <<<PROMPT
Wygeneruj tagi dla tego obrazu.{$contextInfo}

WYMAGANIA:
- 4-6 tagow oddzielonych przecinkami
- Po polsku
- Przydatne do kategoryzacji i wyszukiwania
- Zwiazane z tematyka medyczna jezeli pasuje

Odpowiedz TYLKO lista tagow oddzielonych przecinkami, bez dodatkowego tekstu.
Przyklad: zdrowie, lekarz, konsultacja, pacjent, klinika
PROMPT;
    }
}
