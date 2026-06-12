<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    private const CATEGORIES = [
        'Berita' => 'Berita',
        'Akademik' => 'Akademik',
        'Lingkungan' => 'Lingkungan',
        'Event' => 'Event',
        'Teknologi' => 'Teknologi',
        'Kesehatan' => 'Kesehatan',
        'Prestasi' => 'Prestasi',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? ''),
                            ))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        TextInput::make('excerpt')
                            ->label('Ringkasan')
                            ->maxLength(300)
                            ->hint('Maksimal 300 karakter. Digunakan untuk SEO dan preview.')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Konten Artikel')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts/attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Konten Tambahan')
                    ->description('Tambahkan blok gambar opsional yang ditampilkan di bawah konten utama.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Repeater::make('blocks')
                            ->label('')
                            ->schema([
                                Select::make('type')
                                    ->label('Jenis Blok')
                                    ->options([
                                        'image_cover' => '🖼️  Cover Image — satu gambar penuh lebar',
                                        'image_carousel' => '🎠  Carousel — slider beberapa gambar',
                                        'image_gallery' => '🖼️  Galeri — grid beberapa gambar',
                                    ])
                                    ->required()
                                    ->live()
                                    ->native(false)
                                    ->columnSpanFull(),

                                // ── Cover Image ──────────────────────────────
                                FileUpload::make('image')
                                    ->label('Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('posts/blocks')
                                    ->visibility('public')
                                    ->automaticallyResizeImagesToWidth('1400')
                                    ->hint('Lebar optimal 1400px atau lebih.')
                                    ->visible(fn (Get $get): bool => $get('type') === 'image_cover')
                                    ->columnSpanFull(),

                                TextInput::make('caption')
                                    ->label('Keterangan Gambar')
                                    ->maxLength(200)
                                    ->placeholder('Opsional — keterangan singkat di bawah gambar')
                                    ->visible(fn (Get $get): bool => $get('type') === 'image_cover')
                                    ->columnSpanFull(),

                                // ── Carousel & Gallery — shared images repeater ──
                                Repeater::make('images')
                                    ->label('Daftar Gambar')
                                    ->schema([
                                        FileUpload::make('image')
                                            ->label('Gambar')
                                            ->image()
                                            ->disk('public')
                                            ->directory('posts/blocks')
                                            ->visibility('public')
                                            ->required()
                                            ->columnSpanFull(),

                                        TextInput::make('caption')
                                            ->label('Keterangan')
                                            ->maxLength(200)
                                            ->placeholder('Opsional')
                                            ->columnSpanFull(),
                                    ])
                                    ->addActionLabel('+ Tambah Gambar')
                                    ->minItems(1)
                                    ->defaultItems(1)
                                    ->reorderable()
                                    ->collapsed(false)
                                    ->itemLabel(fn (array $state): string => $state['caption'] ?: 'Gambar')
                                    ->visible(fn (Get $get): bool => in_array($get('type'), ['image_carousel', 'image_gallery']))
                                    ->columnSpanFull(),

                                // ── Gallery columns selector ──────────────────
                                Select::make('columns')
                                    ->label('Jumlah Kolom')
                                    ->options(['2' => '2 Kolom', '3' => '3 Kolom', '4' => '4 Kolom'])
                                    ->default('3')
                                    ->native(false)
                                    ->visible(fn (Get $get): bool => $get('type') === 'image_gallery'),
                            ])
                            ->addActionLabel('+ Tambah Blok')
                            ->reorderable()
                            ->collapsible()
                            ->collapsed()
                            ->defaultItems(0)
                            ->itemLabel(fn (array $state): string => match ($state['type'] ?? '') {
                                'image_cover' => '🖼️  Cover Image',
                                'image_carousel' => '🎠  Carousel — '.count($state['images'] ?? []).' gambar',
                                'image_gallery' => '🖼️🖼️  Galeri — '.count($state['images'] ?? []).' gambar',
                                default => 'Blok Baru',
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Media & Kategori')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Utama')
                            ->image()
                            ->disk('public')
                            ->directory('posts/images')
                            ->visibility('public')
                            ->automaticallyCropImagesToAspectRatio('16:9')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('675')
                            ->hint('Rasio 16:9 disarankan. Akan di-resize ke 1200×675.')
                            ->columnSpanFull(),

                        Select::make('category')
                            ->label('Kategori')
                            ->options(self::CATEGORIES)
                            ->required()
                            ->default('Berita')
                            ->native(false),

                        TextInput::make('read_time')
                            ->label('Estimasi Baca (menit)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60)
                            ->default(3),
                    ])
                    ->columns(2),

                Section::make('Penulis & Publikasi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('author')
                                    ->label('Nama Penulis')
                                    ->required()
                                    ->default('Admin'),

                                TextInput::make('author_initials')
                                    ->label('Inisial Penulis')
                                    ->required()
                                    ->default('AD')
                                    ->maxLength(3)
                                    ->hint('Contoh: AF, SR, BS'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publikasikan')
                                    ->default(false)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, bool $state): void {
                                        if ($state) {
                                            $set('published_at', now()->format('Y-m-d H:i:s'));
                                        }
                                    }),

                                DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->native(false)
                                    ->seconds(false),
                            ]),
                    ]),
            ]);
    }
}
