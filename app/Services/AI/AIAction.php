<?php

declare(strict_types=1);

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

    /**
     * Zwraca etykiete akcji do wyswietlenia w UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::GENERATE_ARTICLE => 'Generuj artykul',
            self::EXPAND_TEXT => 'Rozwin tekst',
            self::IMPROVE_STYLE => 'Popraw styl',
            self::SIMPLIFY => 'Uprosc jezyk',
            self::FORMAT_HTML => 'Formatuj HTML',
            self::GENERATE_SEO => 'Generuj SEO',
            self::SUGGEST_TITLES => 'Sugeruj tytuly',
            self::SUMMARIZE => 'Streszczenie',
            self::TRANSLATE => 'Tlumacz',
        };
    }

    /**
     * Zwraca opis akcji.
     */
    public function description(): string
    {
        return match ($this) {
            self::GENERATE_ARTICLE => 'Tworzy caly artykul od zera na podstawie tematu',
            self::EXPAND_TEXT => 'Rozwija zaznaczony fragment o wiecej szczegolow',
            self::IMPROVE_STYLE => 'Korekta gramatyczna i stylistyczna',
            self::SIMPLIFY => 'Prostszy jezyk dla pacjentow',
            self::FORMAT_HTML => 'Dodaje klasy Tailwind i semantyczny HTML',
            self::GENERATE_SEO => 'Tworzy meta title, description i keywords',
            self::SUGGEST_TITLES => 'Proponuje 5 wariantow tytulu',
            self::SUMMARIZE => 'Tworzy krotkie podsumowanie / lead',
            self::TRANSLATE => 'Tlumaczenie tekstu',
        };
    }

    /**
     * Czy akcja wymaga zaznaczonego tekstu.
     */
    public function requiresSelection(): bool
    {
        return match ($this) {
            self::EXPAND_TEXT, self::IMPROVE_STYLE, self::SIMPLIFY, self::FORMAT_HTML, self::TRANSLATE => true,
            default => false,
        };
    }
}
