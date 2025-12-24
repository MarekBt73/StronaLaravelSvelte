<?php

declare(strict_types=1);

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use App\Models\Media;
use App\Services\AI\AIAction;
use App\Services\AI\AIManager;
use App\Services\AI\DTOs\AIRequest;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('regenerateAI')
                ->label('Regeneruj AI')
                ->icon('heroicon-o-sparkles')
                ->color('primary')
                ->visible(fn (): bool => $this->record->isImage())
                ->requiresConfirmation()
                ->modalHeading('Regeneruj metadane AI')
                ->modalDescription('Czy na pewno chcesz nadpisac istniejace metadane nowymi wygenerowanymi przez AI?')
                ->action(function () {
                    $this->generateAIMetadata($this->record);
                }),

            Actions\Action::make('viewFile')
                ->label('Otworz plik')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(fn (): string => $this->record->url)
                ->openUrlInNewTab(),

            Actions\DeleteAction::make(),
        ];
    }

    private function generateAIMetadata(Media $media): void
    {
        try {
            $aiManager = app(AIManager::class);
            $request = new AIRequest(
                action: AIAction::DESCRIBE_IMAGE,
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

                // Refresh the form
                $this->fillForm();

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
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
