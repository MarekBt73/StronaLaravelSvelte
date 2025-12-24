<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use App\Services\AI\AIAction;
use App\Services\AI\AIManager;
use App\Services\AI\DTOs\AIRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Multimedia';

    protected static ?string $modelLabel = 'Media';

    protected static ?string $pluralModelLabel = 'Biblioteka mediow';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Podglad')
                            ->schema([
                                Forms\Components\Placeholder::make('preview')
                                    ->label('')
                                    ->content(function (?Media $record): \Illuminate\Support\HtmlString|string {
                                        if (! $record || ! $record->isImage()) {
                                            return 'Brak podgladu';
                                        }

                                        return new \Illuminate\Support\HtmlString(
                                            '<img src="' . e($record->url) . '" class="max-w-full max-h-96 rounded-lg shadow" alt="' . e($record->alt_text ?? $record->name) . '">'
                                        );
                                    })
                                    ->extraAttributes(['class' => 'flex justify-center'])
                                    ->dehydrated(false),
                            ])
                            ->hidden(fn (?Media $record) => $record === null || ! $record->isImage()),

                        Forms\Components\Section::make('Metadane AI')
                            ->icon('heroicon-o-sparkles')
                            ->description('Automatycznie wygenerowane opisy i tagi')
                            ->schema([
                                Forms\Components\TextInput::make('alt_text')
                                    ->label('Tekst alternatywny (ALT)')
                                    ->helperText('Opis dla osob niewidomych i SEO (max 125 znakow)')
                                    ->maxLength(125)
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateAlt')
                                            ->icon('heroicon-o-sparkles')
                                            ->tooltip('Generuj z AI')
                                            ->action(function (Forms\Get $get, Forms\Set $set, ?Media $record) {
                                                if (! $record || ! $record->isImage()) {
                                                    Notification::make()
                                                        ->title('Blad')
                                                        ->body('Generowanie ALT dostepne tylko dla obrazow.')
                                                        ->danger()
                                                        ->send();

                                                    return;
                                                }

                                                try {
                                                    $aiManager = app(AIManager::class);
                                                    $request = new AIRequest(
                                                        action: AIAction::DESCRIBE_IMAGE,
                                                        content: $record->name,
                                                        options: [
                                                            'image_path' => $record->path,
                                                            'disk' => $record->disk,
                                                        ],
                                                    );

                                                    $response = $aiManager->generate($request);

                                                    if ($response->success) {
                                                        $data = json_decode(
                                                            preg_replace('/```json\s*|\s*```/', '', $response->content),
                                                            true
                                                        );

                                                        if (isset($data['alt_text'])) {
                                                            $set('alt_text', mb_substr($data['alt_text'], 0, 125));
                                                        }
                                                        if (isset($data['description'])) {
                                                            $set('description', $data['description']);
                                                        }
                                                        if (isset($data['tags'])) {
                                                            $set('tags', $data['tags']);
                                                        }

                                                        $set('ai_generated', true);

                                                        Notification::make()
                                                            ->title('Sukces')
                                                            ->body('Metadane AI zostaly wygenerowane.')
                                                            ->success()
                                                            ->send();
                                                    } else {
                                                        Notification::make()
                                                            ->title('Blad AI')
                                                            ->body($response->error ?? 'Nieznany blad')
                                                            ->danger()
                                                            ->send();
                                                    }
                                                } catch (\Exception $e) {
                                                    Notification::make()
                                                        ->title('Blad')
                                                        ->body('Nie udalo sie wygenerowac opisu: ' . $e->getMessage())
                                                        ->danger()
                                                        ->send();
                                                }
                                            })
                                    ),

                                Forms\Components\Textarea::make('description')
                                    ->label('Opis')
                                    ->rows(2)
                                    ->helperText('Dluzszy opis zawartosci obrazu'),

                                Forms\Components\TextInput::make('tags')
                                    ->label('Tagi')
                                    ->helperText('Oddzielone przecinkami'),

                                Forms\Components\Toggle::make('ai_generated')
                                    ->label('Wygenerowano przez AI')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->collapsible()
                            ->collapsed(fn (?Media $record) => $record !== null && empty($record->alt_text)),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informacje')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nazwa')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Placeholder::make('file_name')
                                    ->label('Nazwa pliku')
                                    ->content(fn (?Media $record): string => $record?->file_name ?? '-'),

                                Forms\Components\Placeholder::make('mime_type')
                                    ->label('Typ MIME')
                                    ->content(fn (?Media $record): string => $record?->mime_type ?? '-'),

                                Forms\Components\Placeholder::make('formatted_size')
                                    ->label('Rozmiar')
                                    ->content(fn (?Media $record): string => $record?->formatted_size ?? '-'),

                                Forms\Components\Placeholder::make('dimensions')
                                    ->label('Wymiary')
                                    ->content(fn (?Media $record): string => $record && $record->width
                                        ? "{$record->width} x {$record->height} px"
                                        : '-'),

                                Forms\Components\Select::make('folder')
                                    ->label('Folder')
                                    ->options(fn () => Media::query()
                                        ->whereNotNull('folder')
                                        ->distinct()
                                        ->pluck('folder', 'folder')
                                        ->toArray())
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('folder')
                                            ->label('Nazwa folderu')
                                            ->required(),
                                    ])
                                    ->createOptionUsing(fn (array $data) => $data['folder']),

                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Utworzono')
                                    ->content(fn (?Media $record): string => $record?->created_at?->format('d.m.Y H:i') ?? '-'),
                            ]),

                        Forms\Components\Section::make('Warianty')
                            ->schema([
                                Forms\Components\Placeholder::make('variants_info')
                                    ->label('')
                                    ->content(function (?Media $record): string {
                                        if (! $record || ! $record->variants) {
                                            return 'Brak wariantow';
                                        }

                                        $html = '<div class="space-y-1 text-sm">';
                                        foreach ($record->variants as $name => $path) {
                                            $html .= '<div class="flex justify-between">';
                                            $html .= '<span class="font-medium">' . ucfirst($name) . ':</span>';
                                            $html .= '<span class="text-gray-500">' . basename($path) . '</span>';
                                            $html .= '</div>';
                                        }
                                        $html .= '</div>';

                                        return $html;
                                    })
                                    ->dehydrated(false),
                            ])
                            ->hidden(fn (?Media $record) => $record === null || empty($record->variants)),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Podglad')
                    ->disk('public')
                    ->square()
                    ->size(80),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nazwa')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('type')
                    ->label('Typ')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'image' => 'Obraz',
                        'video' => 'Video',
                        'document' => 'Dokument',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'video' => 'warning',
                        'document' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('formatted_size')
                    ->label('Rozmiar'),

                Tables\Columns\TextColumn::make('dimensions')
                    ->label('Wymiary')
                    ->getStateUsing(fn (Media $record): string => $record->width
                        ? "{$record->width}x{$record->height}"
                        : '-'),

                Tables\Columns\IconColumn::make('ai_generated')
                    ->label('AI')
                    ->boolean()
                    ->trueIcon('heroicon-o-sparkles')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('primary'),

                Tables\Columns\TextColumn::make('alt_text')
                    ->label('ALT')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('folder')
                    ->label('Folder')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'image' => 'Obrazy',
                        'video' => 'Video',
                        'document' => 'Dokumenty',
                    ]),

                Tables\Filters\SelectFilter::make('folder')
                    ->label('Folder')
                    ->options(fn () => Media::query()
                        ->whereNotNull('folder')
                        ->distinct()
                        ->pluck('folder', 'folder')
                        ->toArray()),

                Tables\Filters\TernaryFilter::make('ai_generated')
                    ->label('Opis AI'),
            ])
            ->actions([
                Tables\Actions\Action::make('generateAI')
                    ->label('AI')
                    ->icon('heroicon-o-sparkles')
                    ->color('primary')
                    ->tooltip('Generuj metadane AI')
                    ->visible(fn (Media $record): bool => $record->isImage())
                    ->action(function (Media $record) {
                        try {
                            $aiManager = app(AIManager::class);
                            $request = new AIRequest(
                                action: AIAction::DESCRIBE_IMAGE,
                                content: $record->name,
                                options: [
                                    'image_path' => $record->path,
                                    'disk' => $record->disk,
                                ],
                            );

                            $response = $aiManager->generate($request);

                            if ($response->success) {
                                $data = json_decode(
                                    preg_replace('/```json\s*|\s*```/', '', $response->content),
                                    true
                                );

                                $updateData = ['ai_generated' => true];

                                if (isset($data['alt_text'])) {
                                    $updateData['alt_text'] = mb_substr($data['alt_text'], 0, 125);
                                }
                                if (isset($data['description'])) {
                                    $updateData['description'] = $data['description'];
                                }
                                if (isset($data['tags'])) {
                                    $updateData['tags'] = $data['tags'];
                                }

                                $record->update($updateData);

                                Notification::make()
                                    ->title('Sukces')
                                    ->body('Metadane AI zostaly wygenerowane.')
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Blad AI')
                                    ->body($response->error ?? 'Nieznany blad')
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Blad')
                                ->body('Nie udalo sie wygenerowac opisu.')
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('copyUrl')
                    ->label('URL')
                    ->icon('heroicon-o-clipboard')
                    ->color('gray')
                    ->tooltip('Kopiuj URL')
                    ->action(function (Media $record) {
                        Notification::make()
                            ->title('URL skopiowany')
                            ->body($record->url)
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('generateAIBulk')
                        ->label('Generuj AI dla zaznaczonych')
                        ->icon('heroicon-o-sparkles')
                        ->action(function ($records) {
                            $aiManager = app(AIManager::class);
                            $successCount = 0;
                            $errorCount = 0;

                            foreach ($records as $record) {
                                if (! $record->isImage()) {
                                    continue;
                                }

                                try {
                                    $request = new AIRequest(
                                        action: AIAction::DESCRIBE_IMAGE,
                                        content: $record->name,
                                        options: [
                                            'image_path' => $record->path,
                                            'disk' => $record->disk,
                                        ],
                                    );

                                    $response = $aiManager->generate($request);

                                    if ($response->success) {
                                        $data = json_decode(
                                            preg_replace('/```json\s*|\s*```/', '', $response->content),
                                            true
                                        );

                                        $updateData = ['ai_generated' => true];

                                        if (isset($data['alt_text'])) {
                                            $updateData['alt_text'] = mb_substr($data['alt_text'], 0, 125);
                                        }
                                        if (isset($data['description'])) {
                                            $updateData['description'] = $data['description'];
                                        }
                                        if (isset($data['tags'])) {
                                            $updateData['tags'] = $data['tags'];
                                        }

                                        $record->update($updateData);
                                        $successCount++;
                                    } else {
                                        $errorCount++;
                                    }
                                } catch (\Exception $e) {
                                    $errorCount++;
                                }

                                // Small delay to avoid rate limiting
                                usleep(500000); // 0.5 second
                            }

                            Notification::make()
                                ->title('Generowanie zakonczone')
                                ->body("Sukces: {$successCount}, Bledy: {$errorCount}")
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canManageBlog() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('created_at', 'desc');
    }
}
