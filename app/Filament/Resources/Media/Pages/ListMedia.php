<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use App\Models\Media;
use App\Services\EmbedVideo;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

            Action::make('add_embed')
                ->label('Tambah Embed Video')
                ->icon(Heroicon::OutlinedVideoCamera)
                ->color('gray')
                ->schema([
                    TextInput::make('name')
                        ->label('Judul')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('Video Wisuda Angkatan 2025'),

                    TextInput::make('embed_url')
                        ->label('URL Video')
                        ->required()
                        ->url()
                        ->maxLength(1000)
                        ->placeholder('https://www.youtube.com/watch?v=...')
                        ->helperText('Tempel link video dari YouTube, TikTok, atau Instagram.')
                        ->rule(static fn (): Closure => static function (string $attribute, mixed $value, Closure $fail): void {
                            $provider = EmbedVideo::detectProvider((string) $value);

                            if ($provider === null) {
                                $fail('URL harus dari YouTube, TikTok, atau Instagram.');

                                return;
                            }

                            if (! EmbedVideo::isValid((string) $value)) {
                                $fail('Tidak dapat membaca ID video dari URL. Pastikan ini link video (bukan link profil/halaman).');
                            }
                        }),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->maxLength(1000)
                        ->placeholder('Keterangan singkat tentang video ini...'),
                ])
                ->modalHeading('Tambah Embed Video')
                ->modalDescription('Tanpa upload file — cukup tempel link videonya.')
                ->modalWidth('xl')
                ->action(function (array $data): void {
                    $url = trim($data['embed_url']);

                    Media::create([
                        'name' => $data['name'],
                        'description' => $data['description'] ?? null,
                        'embed_provider' => EmbedVideo::detectProvider($url),
                        'embed_url' => $url,
                        'disk' => 'public',
                        'uploaded_by' => Auth::id(),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Embed video ditambahkan')
                        ->send();
                }),
        ];
    }
}
