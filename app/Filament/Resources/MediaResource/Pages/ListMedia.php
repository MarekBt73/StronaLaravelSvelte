<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Models\Media;
use App\Services\Media\MediaService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('upload')
                ->label('Dodaj pliki')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->form([
                    Forms\Components\FileUpload::make('files')
                        ->label('Pliki')
                        ->multiple()
                        ->maxFiles(10)
                        ->maxSize(204800) // 200MB
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                            'video/mp4',
                            'video/webm',
                            'application/pdf',
                            'text/plain',
                            'text/markdown',
                        ])
                        ->helperText('Obrazy, video, PDF, TXT, MD. Max 200MB na plik.')
                        ->preserveFilenames()
                        ->directory('temp-uploads'),

                    Forms\Components\TextInput::make('folder')
                        ->label('Folder (opcjonalnie)')
                        ->helperText('Np. 2024/12 lub artykuly')
                        ->placeholder(date('Y/m')),

                    Forms\Components\Toggle::make('generate_ai')
                        ->label('Generuj opisy AI dla obrazow')
                        ->default(true)
                        ->helperText('Automatycznie wygeneruj ALT i tagi dla przeslanych obrazow'),
                ])
                ->action(function (array $data) {
                    $mediaService = app(MediaService::class);
                    $uploadedCount = 0;
                    $folder = $data['folder'] ?? date('Y/m');

                    foreach ($data['files'] as $filePath) {
                        try {
                            // Get the uploaded file from temp storage
                            $tempPath = storage_path('app/public/' . $filePath);

                            if (! file_exists($tempPath)) {
                                continue;
                            }

                            $uploadedFile = new \Illuminate\Http\UploadedFile(
                                $tempPath,
                                basename($filePath),
                                mime_content_type($tempPath),
                                null,
                                true
                            );

                            $media = $mediaService->upload($uploadedFile, $folder);

                            // Generate AI if enabled and is image
                            if ($data['generate_ai'] && $media->isImage()) {
                                $this->generateAIMetadata($media);
                            }

                            // Clean up temp file
                            @unlink($tempPath);

                            $uploadedCount++;
                        } catch (\Exception $e) {
                            \Log::error('Media upload error', [
                                'file' => $filePath,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }

                    if ($uploadedCount > 0) {
                        Notification::make()
                            ->title('Pliki przeslane')
                            ->body("Pomyslnie przeslano {$uploadedCount} plik(ow).")
                            ->success()
                            ->send();
                    }
                })
                ->modalHeading('Przeslij pliki')
                ->modalSubmitActionLabel('Przeslij'),

            Actions\CreateAction::make()
                ->label('Dodaj pojedynczy'),
        ];
    }

    private function generateAIMetadata(Media $media): void
    {
        try {
            $aiManager = app(\App\Services\AI\AIManager::class);
            $request = new \App\Services\AI\DTOs\AIRequest(
                action: \App\Services\AI\AIAction::DESCRIBE_IMAGE,
                content: $media->name,
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

                $media->update($updateData);
            }
        } catch (\Exception $e) {
            \Log::error('AI metadata generation failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
