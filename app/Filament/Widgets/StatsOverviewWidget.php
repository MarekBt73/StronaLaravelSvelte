<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedArticles = Article::where('is_published', true)->count();
        $draftArticles = $totalArticles - $publishedArticles;
        $totalViews = Article::sum('views');
        $mediaCount = Media::count();
        $categoriesCount = Category::count();

        return [
            Stat::make('Artykuly', $totalArticles)
                ->description("{$publishedArticles} opublikowanych, {$draftArticles} szkicow")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary')
                ->chart($this->getArticlesChartData()),

            Stat::make('Wyswietlenia', number_format($totalViews))
                ->description('Lacznie wszystkich artykulow')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),

            Stat::make('Pliki mediow', $mediaCount)
                ->description('W bibliotece mediow')
                ->descriptionIcon('heroicon-m-photo')
                ->color('success'),

            Stat::make('Kategorie', $categoriesCount)
                ->description('Kategorie artykulow')
                ->descriptionIcon('heroicon-m-folder')
                ->color('warning'),
        ];
    }

    private function getArticlesChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = Article::whereDate('created_at', $date)->count();
        }

        return $data;
    }
}
