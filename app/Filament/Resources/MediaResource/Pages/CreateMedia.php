<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Services\Media\MediaService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Http\UploadedFile;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Przeslij plik')
                ->schema([
                    Forms\Components\FileUpload::make('file')
                        ->label('Plik')
                        ->required()
                        ->maxSize(10240) // 10MB
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
                        ->helperText('Obrazy, video, PDF, TXT, MD. Max 10MB.')
                        ->preserveFilenames()
                        ->directory('temp-uploads')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('folder')
                        ->label('Folder')
                        ->helperText('Np. 2024/12 lub artykuly')
                        ->placeholder(date('Y/m')),

                    Forms\Components\Toggle::make('generate_ai')
                        ->label('Generuj opis AI')
                        ->default(true)
                        ->helperText('Automatycznie wygeneruj ALT i tagi (tylko dla obrazow)'),
                ])
                ->columns(2),
        ];
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $mediaService = app(MediaService::class);

        $filePath = $data['file'];
        $tempPath = storage_path('app/public/' . $filePath);

        if (! file_exists($tempPath)) {
            throw new \Exception('Plik nie zostal znaleziony.');
        }

        $uploadedFile = new UploadedFile(
            $tempPath,
            basename($filePath),
            mime_content_type($tempPath),
            null,
            true
        );

        $folder = $data['folder'] ?? date('Y/m');
        $media = $mediaService->upload($uploadedFile, $folder);

        // Generate AI metadata if enabled and is image
        if ($data['generate_ai'] && $media->isImage()) {
            $this->generateAIMetadata($media);
        }

        // Clean up temp file
        @unlink($tempPath);

        return $media;
    }

    private function generateAIMetadata(\App\Models\Media $media): void
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

                Notification::make()
                    ->title('AI')
                    ->body('Metadane zostaly wygenerowane automatycznie.')
                    ->success()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Uwaga')
                ->body('Nie udalo sie wygenerowac metadanych AI.')
                ->warning()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
