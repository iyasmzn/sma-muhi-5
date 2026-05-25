<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('File')
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
