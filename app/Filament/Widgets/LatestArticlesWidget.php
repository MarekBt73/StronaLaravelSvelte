<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

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
                    ->limit(50)
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

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Edytuj')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Article $record): string => ArticleResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
