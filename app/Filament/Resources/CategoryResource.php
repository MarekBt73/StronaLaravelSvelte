<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $modelLabel = 'Kategoria';

    protected static ?string $pluralModelLabel = 'Kategorie';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informacje podstawowe')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nazwa')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->label('Typ kategorii')
                            ->options([
                                'news' => 'Aktualności',
                                'medical' => 'Medyczna',
                            ])
                            ->default('news')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Opis')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Ustawienia')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktywna')
                            ->default(true),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Kolejność')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'news' => 'Aktualności',
                        'medical' => 'Medyczna',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match($state) {
                        'news' => 'info',
                        'medical' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('articles_count')
                    ->label('Artykuły')
                    ->counts('articles')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktywna')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Kolejność')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Utworzono')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'news' => 'Aktualności',
                        'medical' => 'Medyczna',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
