<?php

declare(strict_types=1);

namespace App\Services\AI\Prompts;

class ArticlePrompts
{
    /**
     * Prompt systemowy dla wszystkich akcji zwiazanych z artykulami.
     */
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
7. Nie uzywaj emoji

Odpowiadaj TYLKO wygenerowana trescia, bez dodatkowych komentarzy, wstepow czy zakonczenia typu "Oto artykul".
PROMPT;
    }

    /**
     * Prompt do generowania pelnego artykulu.
     *
     * @param array<string, mixed> $options
     */
    public static function getGenerateArticlePrompt(string $topic, array $options = []): string
    {
        $tone = $options['tone'] ?? 'professional_medical';
        $length = $options['length'] ?? 'medium';
        $includeCta = $options['include_cta'] ?? true;

        $lengthGuide = match ($length) {
            'short' => '400-600 slow',
            'medium' => '800-1000 slow',
            'long' => '1200-1500 slow',
            default => '800-1000 slow',
        };

        $toneGuide = match ($tone) {
            'professional_medical' => 'profesjonalny medyczny, ale zrozumialy dla pacjentow',
            'friendly' => 'przyjazny i przystepny, jak rozmowa z zaufanym lekarzem',
            'formal' => 'formalny i rzeczowy',
            'educational' => 'edukacyjny, wyjasniajacy krok po kroku',
            default => 'profesjonalny medyczny',
        };

        $ctaSection = $includeCta
            ? 'Dodaj na koncu sekcje CTA zachecajaca do umowienia wizyty w klinice MedVita.'
            : 'Nie dodawaj sekcji CTA.';

        return <<<PROMPT
Napisz artykul na temat: {$topic}

Wymagania:
- Dlugosc: {$lengthGuide}
- Ton: {$toneGuide}
- Struktura: lead (1-2 zdania), kluczowe punkty (lista), rozwinecie tematu z sekcjami h2/h3, podsumowanie
- {$ctaSection}
- Na poczatku dodaj sekcje "Kluczowe informacje" jako liste punktow (dla wyszukiwarek AI)
- Dodaj zastrzezenie o konsultacji z lekarzem

Uzyj formatowania HTML z klasami Tailwind zgodnie z wytycznymi.
PROMPT;
    }

    /**
     * Prompt do rozwijania tekstu.
     */
    public static function getExpandTextPrompt(string $text): string
    {
        return <<<PROMPT
Rozwin ponizszy tekst, dodajac wiecej szczegolow, przykladow i wyjasnnien.
Zachowaj oryginalny ton i styl. Uzyj formatowania HTML/Tailwind.
Tekst powinien byc 2-3 razy dluzszy niz oryginal.

Tekst do rozwinieciu:
{$text}
PROMPT;
    }

    /**
     * Prompt do poprawiania stylu.
     */
    public static function getImproveStylePrompt(string $text): string
    {
        return <<<PROMPT
Popraw styl i gramatyke ponizszego tekstu.
- Popraw bledy jezykowe i ortograficzne
- Ulepsz plynnosc i czytelnosc
- Zachowaj sens i strukture
- Zachowaj formatowanie HTML jesli jest obecne
- Uzyj profesjonalnego, ale przystepnego tonu

Tekst do poprawy:
{$text}
PROMPT;
    }

    /**
     * Prompt do upraszczania tekstu.
     */
    public static function getSimplifyPrompt(string $text): string
    {
        return <<<PROMPT
Uprość ponizszy tekst tak, aby byl zrozumialy dla przecietnego pacjenta bez wyksztalcenia medycznego.
- Zamien zargon medyczny na proste wyjasnienia
- Uzyj krotszych zdan
- Dodaj przyklady z codziennego zycia jesli to pomoze
- Zachowaj formatowanie HTML jesli jest obecne

Tekst do uproszczenia:
{$text}
PROMPT;
    }

    /**
     * Prompt do formatowania HTML.
     */
    public static function getFormatHtmlPrompt(string $text): string
    {
        $shortGuide = StyleGuide::getShort();

        return <<<PROMPT
Sformatuj ponizszy tekst jako poprawny HTML z klasami Tailwind CSS.
- Dodaj odpowiednie naglowki (h2, h3)
- Zamien wyliczenia na listy HTML
- Dodaj wyroznienia dla waznych terminow
- Dodaj callout-y dla waznych informacji
- Uzyj klas z palety 'medical' dla kolorow

{$shortGuide}

Tekst do formatowania:
{$text}
PROMPT;
    }

    /**
     * Prompt do tworzenia streszczenia.
     */
    public static function getSummarizePrompt(string $text): string
    {
        return <<<PROMPT
Stworz krotkie streszczenie (2-3 zdania) ponizszego tekstu.
Streszczenie powinno:
- Zawierac najwazniejsze informacje
- Byc napisane prostym jezykiem
- Zachecac do przeczytania calego artykulu
- Nie zawierac formatowania HTML (czysty tekst)

Tekst do streszczenia:
{$text}
PROMPT;
    }

    /**
     * Prompt do tlumaczenia.
     */
    public static function getTranslatePrompt(string $text, string $targetLanguage): string
    {
        $langName = match ($targetLanguage) {
            'en' => 'angielski',
            'de' => 'niemiecki',
            'uk' => 'ukrainski',
            default => $targetLanguage,
        };

        return <<<PROMPT
Przetlumacz ponizszy tekst na jezyk {$langName}.
- Zachowaj formatowanie HTML jesli jest obecne
- Zachowaj profesjonalny ton medyczny
- Uzywaj poprawnej terminologii medycznej w jezyku docelowym

Tekst do tlumaczenia:
{$text}
PROMPT;
    }
}
