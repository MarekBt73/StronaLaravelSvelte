<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                                Forms\Components\Toggle::make('use_media_library')
                                    ->label('Wybierz z biblioteki mediow')
                                    ->default(false)
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateHydrated(function (Forms\Components\Toggle $component, ?Article $record) {
                                        // If featured_image_id exists, use media library
                                        $component->state($record?->featured_image_id !== null);
                                    }),

                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Przeslij zdjecie')
                                    ->image()
                                    ->directory('articles')
                                    ->imageEditor()
                                    ->columnSpanFull()
                                    ->visible(fn (Forms\Get $get): bool => ! $get('use_media_library')),

                                Forms\Components\Select::make('featured_image_id')
                                    ->label('Wybierz z galerii')
                                    ->options(fn () => Media::query()
                                        ->where('type', 'image')
                                        ->orderBy('created_at', 'desc')
                                        ->limit(100)
                                        ->get()
                                        ->mapWithKeys(fn (Media $media) => [
                                            $media->id => $media->name . ' (' . $media->formatted_size . ')',
                                        ]))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpanFull()
                                    ->visible(fn (Forms\Get $get): bool => $get('use_media_library'))
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?int $state) {
                                        if ($state) {
                                            $media = Media::find($state);
                                            if ($media && $media->alt_text) {
                                                $set('featured_image_alt', $media->alt_text);
                                            }
                                        }
                                    })
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('viewMedia')
                                            ->icon('heroicon-o-eye')
                                            ->tooltip('Podglad')
                                            ->url(fn (Forms\Get $get): ?string => $get('featured_image_id')
                                                ? route('filament.admin.resources.media.edit', ['record' => $get('featured_image_id')])
                                                : null)
                                            ->openUrlInNewTab()
                                            ->visible(fn (Forms\Get $get): bool => $get('featured_image_id') !== null)
                                    ),

                                Forms\Components\Placeholder::make('media_preview')
                                    ->label('Podglad')
                                    ->content(function (Forms\Get $get): string {
                                        $mediaId = $get('featured_image_id');
                                        if (! $mediaId) {
                                            return '';
                                        }
                                        $media = Media::find($mediaId);
                                        if (! $media) {
                                            return '';
                                        }

                                        return '<img src="' . $media->url . '" class="max-h-48 rounded-lg shadow" alt="' . e($media->alt_text ?? $media->name) . '">';
                                    })
                                    ->visible(fn (Forms\Get $get): bool => $get('use_media_library') && $get('featured_image_id') !== null)
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('featured_image_alt')
                                    ->label('Opis alternatywny (ALT)')
                                    ->helperText('Opis zdjęcia dla osób niewidomych i SEO')
                                    ->maxLength(255)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateImageAlt')
                                            ->icon('heroicon-o-sparkles')
                                            ->tooltip('Generuj z AI')
                                            ->visible(fn (Forms\Get $get): bool => $get('use_media_library') && $get('featured_image_id') !== null)
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $mediaId = $get('featured_image_id');
                                                if (! $mediaId) {
                                                    return;
                                                }
                                                $media = Media::find($mediaId);
                                                if (! $media) {
                                                    return;
                                                }

                                                try {
                                                    $aiManager = app(\App\Services\AI\AIManager::class);
                                                    $request = new \App\Services\AI\DTOs\AIRequest(
                                                        action: \App\Services\AI\AIAction::DESCRIBE_IMAGE,
                                                        content: $get('title') ?? $media->name,
                                                        options: [
                                                            'image_path' => $media->path,
                                                            'disk' => $media->disk,
                                                        ],
                                                    );

                                                    $response = $aiManager->generate($request);

                                                    if ($response->success) {
                                                        $data = json_decode(
                                                            preg_replace('/```json\s*|\s*```/', '', $response->content),
                                                            true
                                                        );

                                                        if (isset($data['alt_text'])) {
                                                            $set('featured_image_alt', mb_substr($data['alt_text'], 0, 125));

                                                            Notification::make()
                                                                ->title('Sukces')
                                                                ->body('Tekst ALT zostal wygenerowany.')
                                                                ->success()
                                                                ->send();
                                                        }
                                                    }
                                                } catch (\Exception $e) {
                                                    Notification::make()
                                                        ->title('Blad')
                                                        ->body('Nie udalo sie wygenerowac opisu.')
                                                        ->danger()
                                                        ->send();
                                                }
                                            })
                                    ),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta tytuł')
                                    ->maxLength(60)
                                    ->helperText('Pozostaw puste, aby użyć tytułu artykułu')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateMetaTitle')
                                            ->icon('heroicon-o-sparkles')
                                            ->tooltip('Generuj z AI')
                                            ->action(function (Forms\Get $get, Forms\Set $set) {
                                                $title = $get('title');
                                                $content = $get('content');
                                                if (empty($title) || empty($content)) {
                                                    return;
                                                }
                                                $aiManager = app(\App\Services\AI\AIManager::class);
                                                $request = new \App\Services\AI\DTOs\AIRequest(
                                                    action: \App\Services\AI\AIAction::GENERATE_SEO,
                                                    content: $content,
                                                    options: ['title' => $title],
                                                );
                                                $response = $aiManager->generate($request);
                                                if ($response->success) {
                                                    $data = json_decode(preg_replace('/```json\s*|```\s*/', '', $response->content), true);
                                                    if (isset($data['meta_title'])) {
                                                        // Obetnij do 60 znaków
                                                        $set('meta_title', \App\Services\AI\Prompts\SEOPrompts::truncate($data['meta_title'], 60));
                                                    }
                                                    if (isset($data['meta_description'])) {
                                                        // Obetnij do 160 znaków
                                                        $set('meta_description', \App\Services\AI\Prompts\SEOPrompts::truncate($data['meta_description'], 160));
                                                    }
                                                    if (isset($data['keywords'])) {
                                                        $set('meta_keywords', $data['keywords']);
                                                    }
                                                }
                                            })
                                    ),

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

                        Forms\Components\Section::make('AI Content Assistant')
                            ->icon('heroicon-o-sparkles')
                            ->description('Generuj i poprawiaj treści przy pomocy sztucznej inteligencji')
                            ->schema([
                                Forms\Components\View::make('livewire.ai-content-panel-embed'),
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
