<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\Models\ArticleView;
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

        // Pobierz rzeczywiste dane z ostatnich 30 dni
        try {
            $chartData = ArticleView::getChartData($record->id, 30);
        } catch (\Throwable $e) {
            // Jesli tabela nie istnieje, zwroc puste dane
            $chartData = ['labels' => [], 'views' => [], 'unique' => []];
        }

        $labelsJson = json_encode($chartData['labels']);
        $viewsJson = json_encode($chartData['views']);
        $uniqueJson = json_encode($chartData['unique']);

        $totalViews = array_sum($chartData['views']);
        $totalUnique = array_sum($chartData['unique']);

        return <<<HTML
        <div class="p-4">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-primary-600">{$totalViews}</p>
                    <p class="text-sm text-gray-500">Wyswietlenia (30 dni)</p>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-success-600">{$totalUnique}</p>
                    <p class="text-sm text-gray-500">Unikalnych (30 dni)</p>
                </div>
            </div>
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
                            datasets: [
                                {
                                    label: 'Wyswietlenia',
                                    data: {$viewsJson},
                                    backgroundColor: 'rgba(14, 165, 142, 0.2)',
                                    borderColor: 'rgb(14, 165, 142)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.3
                                },
                                {
                                    label: 'Unikalni',
                                    data: {$uniqueJson},
                                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                    borderColor: 'rgb(59, 130, 246)',
                                    borderWidth: 2,
                                    fill: false,
                                    tension: 0.3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'bottom'
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
