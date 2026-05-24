<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Gambar')
                ->description('Gambar latar belakang slide. Rasio 16:9, minimal 1280×720px disarankan.')
                ->schema([
                    FileUpload::make('image')
                        ->label('Gambar Slide')
                        ->image()
                        ->disk('public')
                        ->directory('slides')
                        ->visibility('public')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1600')
                        ->imageResizeTargetHeight('900')
                        ->hint('Akan di-resize ke 1600×900px (16:9). Biarkan kosong untuk menggunakan placeholder.')
                        ->columnSpanFull(),
                ]),

            Section::make('Konten')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(200)
                        ->placeholder('Unggul dalam Akademik')
                        ->columnSpanFull(),

                    TextInput::make('subtitle')
                        ->label('Subjudul / Deskripsi')
                        ->maxLength(500)
                        ->placeholder('Raih prestasi terbaik bersama guru-guru berpengalaman.')
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('button_label')
                            ->label('Teks Tombol CTA')
                            ->maxLength(100)
                            ->placeholder('Daftar Sekarang')
                            ->hint('Kosongkan jika tidak perlu tombol.'),

                        TextInput::make('button_url')
                            ->label('URL Tombol CTA')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://... atau #spmb'),
                    ]),
                ]),

            Section::make('Pengaturan')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->hint('Angka kecil tampil lebih dulu.'),

                        Toggle::make('is_active')
                            ->label('Aktif / Tampilkan')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ]),
                ]),
        ]);
    }
}
