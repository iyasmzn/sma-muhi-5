<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditMedia extends EditRecord
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->after(function (): void {
                    Storage::disk($this->record->disk)->delete($this->record->path);
                }),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If a new file was uploaded, update size and mime_type
        if (isset($data['path']) && $data['path'] !== $this->record->path) {
            $disk = Storage::disk($data['disk'] ?? 'public');

            if ($disk->exists($data['path'])) {
                $data['size'] = $disk->size($data['path']);
                $data['mime_type'] = $disk->mimeType($data['path']);
                // Delete old file
                $disk->delete($this->record->path);
            }
        }

        return $data;
    }
}
