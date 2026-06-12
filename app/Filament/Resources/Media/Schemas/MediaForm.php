<?php

namespace App\Filament\Resources\Media\Schemas;

use App\Services\EmbedVideo;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Hidden::make('embed_provider'),

            Section::make('File')
                ->visible(fn (Get $get): bool => blank($get('embed_provider')))
                ->schema([
                    FileUpload::make('path')
                        ->label('File')
                        ->disk('public')
                        ->directory('media')
                        ->visibility('public')
                        ->image()
                        ->acceptedFileTypes([
                            'image/jpeg', 'image/png', 'image/gif',
                            'image/webp', 'image/svg+xml',
                            'application/pdf',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Embed Video')
                ->description(fn (Get $get): string => 'Sumber: '.EmbedVideo::label($get('embed_provider')))
                ->icon('heroicon-o-video-camera')
                ->visible(fn (Get $get): bool => filled($get('embed_provider')))
                ->schema([
                    TextInput::make('embed_url')
                        ->label('URL Video')
                        ->required()
                        ->url()
                        ->maxLength(1000)
                        ->helperText('Link video YouTube, TikTok, atau Instagram.')
                        ->rule(static fn (): Closure => static function (string $attribute, mixed $value, Closure $fail): void {
                            if (EmbedVideo::detectProvider((string) $value) === null) {
                                $fail('URL harus dari YouTube, TikTok, atau Instagram.');

                                return;
                            }

                            if (! EmbedVideo::isValid((string) $value)) {
                                $fail('Tidak dapat membaca ID video dari URL.');
                            }
                        })
                        ->columnSpanFull(),
                ]),

            Section::make('Informasi SEO')
                ->description('Metadata yang membantu mesin pencari dan aksesibilitas.')
                ->icon('heroicon-o-magnifying-glass')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama File')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('foto-wisuda-2025')
                        ->hint('Nama deskriptif tanpa ekstensi. Digunakan sebagai title SEO.'),

                    TextInput::make('alt')
                        ->label('Alt Text')
                        ->maxLength(500)
                        ->placeholder('Foto wisuda angkatan 2025 di aula sekolah')
                        ->hint('Deskripsi singkat gambar. Wajib untuk aksesibilitas dan SEO.')
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->maxLength(1000)
                        ->placeholder('Keterangan lengkap tentang gambar atau file ini...')
                        ->hint('Opsional. Konteks tambahan untuk kebutuhan SEO atau catatan internal.')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
