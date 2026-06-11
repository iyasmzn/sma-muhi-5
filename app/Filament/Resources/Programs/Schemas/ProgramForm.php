<?php

namespace App\Filament\Resources\Programs\Schemas;

use App\Models\Program;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten')
                    ->schema([
                        TextInput::make('title')
                            ->label('Nama Program')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? ''),
                            ))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(180)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        TextInput::make('excerpt')
                            ->label('Ringkasan')
                            ->maxLength(300)
                            ->hint('Maksimal 300 karakter. Tampil di kartu program & SEO.')
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Deskripsi Program')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('programs/attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Media & Kategori')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Utama')
                            ->image()
                            ->disk('public')
                            ->directory('programs/images')
                            ->visibility('public')
                            ->automaticallyCropImagesToAspectRatio('16:9')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('675')
                            ->hint('Rasio 16:9 disarankan. Akan di-resize ke 1200×675.')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('category')
                                    ->label('Kategori')
                                    ->maxLength(100)
                                    ->datalist([
                                        'Akademik',
                                        'Ekstrakurikuler',
                                        'Keagamaan',
                                        'Karakter',
                                        'Teknologi',
                                    ])
                                    ->hint('Contoh: Akademik, Ekstrakurikuler'),

                                TextInput::make('icon')
                                    ->label('Ikon (Emoji)')
                                    ->maxLength(16)
                                    ->placeholder('📚')
                                    ->hint('Tampil bila tidak ada gambar.'),
                            ]),
                    ])
                    ->columns(2),

                Section::make('Galeri Program')
                    ->description('Unggah beberapa foto kegiatan program. Ditampilkan di halaman detail program.')
                    ->schema([
                        FileUpload::make('gallery')
                            ->label('Foto Galeri')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->disk('public')
                            ->directory('programs/gallery')
                            ->visibility('public')
                            ->panelLayout('grid')
                            ->imageEditor()
                            ->hint('Bisa pilih banyak gambar sekaligus. Tarik untuk mengatur urutan.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Publikasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publikasikan')
                                    ->default(true),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->hint('Angka kecil tampil lebih dahulu.'),
                            ]),

                        Toggle::make('is_featured')
                            ->label('Tampilkan di Landing Page')
                            ->helperText('Maksimal '.Program::MAX_FEATURED.' program unggulan dapat tampil di section beranda.')
                            ->rule(static fn (?Program $record): Closure => static function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                                if (! $value) {
                                    return;
                                }

                                $count = Program::featured()
                                    ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                                    ->count();

                                if ($count >= Program::MAX_FEATURED) {
                                    $fail('Maksimal '.Program::MAX_FEATURED.' program unggulan. Nonaktifkan salah satu program lain terlebih dahulu.');
                                }
                            }),
                    ]),
            ]);
    }
}
