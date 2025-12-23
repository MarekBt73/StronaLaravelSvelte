<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Statyczne strony do przeszukania
     */
    private array $staticPages = [
        [
            'title' => 'O nas',
            'url' => '/o-nas',
            'type' => 'page',
            'content' => 'MedVita to nowoczesne centrum medyczne w Warszawie. Oferujemy kompleksową opiekę zdrowotną, doświadczonych specjalistów i najnowocześniejszy sprzęt diagnostyczny.',
        ],
        [
            'title' => 'Kontakt',
            'url' => '/kontakt',
            'type' => 'page',
            'content' => 'Skontaktuj się z nami. Telefon, email, formularz kontaktowy. Trzy lokalizacje w Warszawie: Centrum, Mokotów, Ursynów.',
        ],
        [
            'title' => 'Lekarz rodzinny - POZ',
            'url' => '/uslugi#podstawowa-opieka-zdrowotna',
            'type' => 'service',
            'content' => 'Podstawowa opieka zdrowotna. Lekarz pierwszego kontaktu, badania profilaktyczne, szczepienia, recepty, zwolnienia lekarskie.',
        ],
        [
            'title' => 'Pediatria',
            'url' => '/uslugi#pediatria',
            'type' => 'service',
            'content' => 'Opieka pediatryczna dla dzieci i młodzieży. Badania rozwojowe, szczepienia, leczenie chorób dziecięcych.',
        ],
        [
            'title' => 'Laboratorium',
            'url' => '/uslugi#laboratorium',
            'type' => 'service',
            'content' => 'Laboratorium diagnostyczne. Badania krwi, moczu, morfologia, biochemia, hormony, markery nowotworowe.',
        ],
        [
            'title' => 'Kardiologia',
            'url' => '/uslugi#kardiologia',
            'type' => 'service',
            'content' => 'Konsultacje kardiologiczne, EKG, echo serca, holter, badania ciśnienia, leczenie nadciśnienia i chorób serca.',
        ],
        [
            'title' => 'Ginekologia',
            'url' => '/uslugi#ginekologia',
            'type' => 'service',
            'content' => 'Konsultacje ginekologiczne, cytologia, USG, prowadzenie ciąży, antykoncepcja, leczenie niepłodności.',
        ],
        [
            'title' => 'Dermatologia',
            'url' => '/uslugi#dermatologia',
            'type' => 'service',
            'content' => 'Dermatolog, choroby skóry, alergie, trądzik, łuszczyca, badanie znamion, dermatoskopia.',
        ],
        [
            'title' => 'Nasi lekarze',
            'url' => '/lekarze',
            'type' => 'page',
            'content' => 'Poznaj naszych specjalistów. Doświadczeni lekarze różnych specjalizacji: internista, pediatra, kardiolog, ginekolog, dermatolog.',
        ],
        [
            'title' => 'Rezerwacja wizyty',
            'url' => '/rezerwacja',
            'type' => 'page',
            'content' => 'Umów wizytę online. Wybierz specjalistę, termin i zarezerwuj wizytę 24/7.',
        ],
    ];

    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];
        $queryLower = mb_strtolower($query);

        // Przeszukaj artykuły bloga
        $articles = Article::query()
            ->published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%");
            })
            ->with('category')
            ->limit(5)
            ->get();

        foreach ($articles as $article) {
            $results[] = [
                'title' => $article->title,
                'url' => '/blog/' . $article->slug,
                'type' => 'article',
                'category' => $article->category?->name ?? 'Blog',
                'excerpt' => $this->getExcerpt($article->excerpt ?? $article->content, $query),
            ];
        }

        // Przeszukaj statyczne strony
        foreach ($this->staticPages as $page) {
            $titleMatch = str_contains(mb_strtolower($page['title']), $queryLower);
            $contentMatch = str_contains(mb_strtolower($page['content']), $queryLower);

            if ($titleMatch || $contentMatch) {
                $results[] = [
                    'title' => $page['title'],
                    'url' => $page['url'],
                    'type' => $page['type'],
                    'category' => $this->getTypeLabel($page['type']),
                    'excerpt' => $this->getExcerpt($page['content'], $query),
                ];
            }
        }

        // Sortuj - artykuły na górze, potem strony
        usort($results, function ($a, $b) {
            $typeOrder = ['article' => 0, 'service' => 1, 'page' => 2];
            return ($typeOrder[$a['type']] ?? 3) <=> ($typeOrder[$b['type']] ?? 3);
        });

        // Limit wyników
        $results = array_slice($results, 0, 10);

        return response()->json([
            'results' => $results,
            'query' => $query,
            'count' => count($results),
        ]);
    }

    private function getExcerpt(string $content, string $query): string
    {
        // Usuń HTML
        $text = strip_tags($content);
        $text = html_entity_decode($text);

        // Znajdź pozycję zapytania
        $pos = mb_stripos($text, $query);

        if ($pos !== false) {
            $start = max(0, $pos - 50);
            $excerpt = mb_substr($text, $start, 150);

            if ($start > 0) {
                $excerpt = '...' . $excerpt;
            }
            if (mb_strlen($text) > $start + 150) {
                $excerpt .= '...';
            }

            return trim($excerpt);
        }

        // Jeśli nie znaleziono, zwróć początek
        return mb_substr($text, 0, 150) . (mb_strlen($text) > 150 ? '...' : '');
    }

    private function getTypeLabel(string $type): string
    {
        return match ($type) {
            'article' => 'Blog',
            'service' => 'Usługa',
            'page' => 'Strona',
            default => 'Inne',
        };
    }
}
