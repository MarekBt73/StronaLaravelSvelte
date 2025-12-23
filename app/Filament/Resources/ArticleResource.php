<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $modelLabel = 'Artykuł';

    protected static ?string $pluralModelLabel = 'Artykuły';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Treść artykułu')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Tytuł')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Zajawka')
                                    ->rows(3)
                                    ->helperText('Krótki opis wyświetlany na liście artykułów'),

                                TinyEditor::make('content')
                                    ->label('Treść')
                                    ->required()
                                    ->columnSpanFull()
                                    ->profile('full')
                                    ->showMenuBar()
                                    ->minHeight(500),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Zdjęcie główne')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Zdjęcie')
                                    ->image()
                                    ->directory('articles')
                                    ->imageEditor()
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('featured_image_alt')
                                    ->label('Opis alternatywny (ALT)')
                                    ->helperText('Opis zdjęcia dla osób niewidomych i SEO')
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta tytuł')
                                    ->maxLength(60)
                                    ->helperText('Pozostaw puste, aby użyć tytułu artykułu'),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta opis')
                                    ->rows(2)
                                    ->maxLength(160)
                                    ->helperText('Maks. 160 znaków'),

                                Forms\Components\TextInput::make('meta_keywords')
                                    ->label('Słowa kluczowe')
                                    ->helperText('Oddzielone przecinkami'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Publikacja')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategoria')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nazwa')
                                            ->required(),
                                        Forms\Components\Select::make('type')
                                            ->label('Typ')
                                            ->options([
                                                'news' => 'Aktualności',
                                                'medical' => 'Medyczna',
                                            ])
                                            ->default('news')
                                            ->required(),
                                    ]),

                                Forms\Components\Select::make('user_id')
                                    ->label('Autor')
                                    ->relationship('author', 'name')
                                    ->default(auth()->id())
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Data publikacji')
                                    ->native(false)
                                    ->displayFormat('d.m.Y H:i'),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Opublikowany')
                                    ->default(false),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Wyróżniony')
                                    ->default(false),
                            ]),

                        Forms\Components\Section::make('Statystyki')
                            ->schema([
                                Forms\Components\Placeholder::make('views')
                                    ->label('Wyświetlenia')
                                    ->content(fn (?Article $record): string => (string) ($record?->views ?? 0)),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Utworzono')
                                    ->content(fn (?Article $record): string => $record?->created_at?->format('d.m.Y H:i') ?? '-'),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Aktualizacja')
                                    ->content(fn (?Article $record): string => $record?->updated_at?->format('d.m.Y H:i') ?? '-'),
                            ])
                            ->hidden(fn (?Article $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Zdjęcie')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategoria')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Opublikowany')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Wyróżniony')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Data publikacji')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views')
                    ->label('Wyświetlenia')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Aktualizacja')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategoria')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status publikacji'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Wyróżniony'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canManageBlog() ?? false;
    }
}
