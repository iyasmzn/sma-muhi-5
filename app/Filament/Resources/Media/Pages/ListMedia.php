<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Models\Media;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->label('Upload File')
                ->icon(Heroicon::OutlinedArrowUpTray)
                ->color('primary')
                ->schema([
                    FileUpload::make('files')
                        ->label('Pilih file (bisa beberapa sekaligus)')
                        ->multiple()
                        ->disk('public')
                        ->directory('media')
                        ->visibility('public')
                        ->image()
                        ->acceptedFileTypes([
                            'image/jpeg', 'image/png', 'image/gif',
                            'image/webp', 'image/svg+xml',
                            'application/pdf',
                        ])
                        ->maxSize(10240)
                        ->columnSpanFull(),
                ])
                ->modalHeading('Upload ke Galeri & Kegiatan Sekolah')
                ->modalDescription('Pilih satu atau beberapa file. Setiap file akan tersimpan sebagai item media terpisah.')
                ->modalWidth('xl')
                ->action(function (array $data): void {
                    $disk = Storage::disk('public');
                    $userId = Auth::id();
                    $count = 0;

                    foreach (($data['files'] ?? []) as $path) {
                        if (! $disk->exists($path)) {
                            continue;
                        }

                        $filename = basename($path);
                        $name = pathinfo($filename, PATHINFO_FILENAME);

                        Media::create([
                            'name' => $name,
                            'path' => $path,
                            'disk' => 'public',
                            'mime_type' => $disk->mimeType($path),
                            'size' => $disk->size($path),
                            'uploaded_by' => $userId,
                        ]);

                        $count++;
                    }

                    Notification::make()
                        ->success()
                        ->title($count === 1 ? '1 file berhasil diupload' : "{$count} file berhasil diupload")
                        ->send();
                }),
        ];
    }
}
