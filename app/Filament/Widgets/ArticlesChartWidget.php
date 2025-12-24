<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ArticlesChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Artykuly w ostatnich 30 dniach';

    protected int|string|array $columnSpan = 2;

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d.m');
            $data[] = Article::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Utworzone artykuly',
                    'data' => $data,
                    'backgroundColor' => 'rgba(14, 165, 142, 0.2)',
                    'borderColor' => 'rgb(14, 165, 142)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
