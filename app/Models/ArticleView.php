<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleView extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'views_count' => 'integer',
        'unique_visitors' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Zapisz wyswietlenie artykulu (dzienny agregat).
     */
    public static function recordView(int $articleId, string $sessionHash): void
    {
        $today = now()->toDateString();

        // Sprawdz czy ta sesja juz dzisiaj odwiedzila ten artykul
        $isNewVisitor = ! ArticleVisitorSession::where('article_id', $articleId)
            ->where('session_hash', $sessionHash)
            ->where('date', $today)
            ->exists();

        // Inkrementuj licznik wyswietlen
        $viewRecord = self::firstOrCreate(
            ['article_id' => $articleId, 'date' => $today],
            ['views_count' => 0, 'unique_visitors' => 0]
        );

        $viewRecord->increment('views_count');

        // Jesli nowy uzytkownik dzisiaj
        if ($isNewVisitor) {
            $viewRecord->increment('unique_visitors');

            // Zapisz sesje
            ArticleVisitorSession::create([
                'article_id' => $articleId,
                'session_hash' => $sessionHash,
                'date' => $today,
            ]);
        }
    }

    /**
     * Pobierz dane do wykresu (ostatnie N dni).
     */
    public static function getChartData(int $articleId, int $days = 30): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();

        $views = self::where('article_id', $articleId)
            ->where('date', '>=', $startDate)
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($item) => $item->date->format('Y-m-d'));

        $labels = [];
        $viewsData = [];
        $uniqueData = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d.m');

            $record = $views->get($dateKey);
            $viewsData[] = $record?->views_count ?? 0;
            $uniqueData[] = $record?->unique_visitors ?? 0;
        }

        return [
            'labels' => $labels,
            'views' => $viewsData,
            'unique' => $uniqueData,
        ];
    }
}
