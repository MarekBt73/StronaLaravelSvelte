<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class LatestArticlesWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Ostatnie artykuly';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Article::query()
                    ->with(['category', 'author'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytul')
                    ->limit(40)
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategoria')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('views')
                    ->label('Wyswietlenia')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('viewStats')
                    ->label('Statystyki')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->modalHeading(fn (Article $record): string => "Statystyki: {$record->title}")
                    ->modalDescription(fn (Article $record): string => "Lacznie wyswietlen: {$record->views}")
                    ->modalContent(fn (Article $record): HtmlString => new HtmlString(
                        $this->getViewsChartHtml($record)
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Zamknij'),

                Tables\Actions\Action::make('edit')
                    ->label('Edytuj')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Article $record): string => ArticleResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }

    private function getViewsChartHtml(Article $record): string
    {
        $chartId = 'views-chart-' . $record->id;

        // Generate sample data for last 30 days (in real app, you'd query actual daily views)
        $labels = [];
        $data = [];
        $totalViews = $record->views;

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d.m');
            // Simulate daily distribution (in production, use actual daily stats table)
            $data[] = $i < 7 ? rand(0, max(1, intval($totalViews / 10))) : rand(0, max(1, intval($totalViews / 20)));
        }

        $labelsJson = json_encode($labels);
        $dataJson = json_encode($data);

        return <<<HTML
        <div class="p-4">
            <canvas id="{$chartId}" height="200"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (function() {
                const ctx = document.getElementById('{$chartId}');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {$labelsJson},
                            datasets: [{
                                label: 'Wyswietlenia',
                                data: {$dataJson},
                                backgroundColor: 'rgba(14, 165, 142, 0.2)',
                                borderColor: 'rgb(14, 165, 142)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            })();
        </script>
        HTML;
    }
}
