<?php

namespace App\Filament\Resources\Teachers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    private const POSITIONS = [
        'Kepala Sekolah' => 'Kepala Sekolah',
        'Wakil Kepala Sekolah' => 'Wakil Kepala Sekolah',
        'Guru Matematika' => 'Guru Matematika',
        'Guru Bahasa Indonesia' => 'Guru Bahasa Indonesia',
        'Guru Bahasa Inggris' => 'Guru Bahasa Inggris',
        'Guru Fisika' => 'Guru Fisika',
        'Guru Kimia' => 'Guru Kimia',
        'Guru Biologi' => 'Guru Biologi',
        'Guru Sejarah' => 'Guru Sejarah',
        'Guru Geografi' => 'Guru Geografi',
        'Guru Ekonomi' => 'Guru Ekonomi',
        'Guru Sosiologi' => 'Guru Sosiologi',
        'Guru Pendidikan Agama' => 'Guru Pendidikan Agama',
        'Guru Seni Budaya' => 'Guru Seni Budaya',
        'Guru Penjasorkes' => 'Guru Penjasorkes',
        'Guru TIK' => 'Guru TIK',
        'Guru BK' => 'Guru BK',
        'Staf Tata Usaha' => 'Staf Tata Usaha',
        'Lainnya' => 'Lainnya',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pribadi')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(150)
                            ->placeholder('Drs. Ahmad Fauzi, M.Pd.'),

                        TextInput::make('nip')
                            ->label('NIP')
                            ->maxLength(30)
                            ->placeholder('197003012005011001')
                            ->hint('Nomor Induk Pegawai (kosongkan jika tidak ada).'),
                    ]),

                    FileUpload::make('photo')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('teachers')
                        ->visibility('public')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:4')
                        ->imageResizeTargetWidth('300')
                        ->imageResizeTargetHeight('400')
                        ->hint('Rasio 3:4 (potret). Akan di-resize ke 300×400px.')
                        ->columnSpanFull(),
                ]),

            Section::make('Jabatan & Bidang Studi')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('position')
                            ->label('Jabatan')
                            ->options(self::POSITIONS)
                            ->required()
                            ->searchable()
                            ->native(false),

                        TextInput::make('subject')
                            ->label('Mata Pelajaran')
                            ->maxLength(100)
                            ->placeholder('Matematika Wajib')
                            ->hint('Kosongkan jika tidak mengajar.'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('education')
                            ->label('Pendidikan Terakhir')
                            ->maxLength(100)
                            ->placeholder('S2 Pendidikan Matematika'),

                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->default(0)
                            ->hint('Angka kecil tampil lebih dulu.'),
                    ]),

                    Toggle::make('is_active')
                        ->label('Aktif Mengajar')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger')
                        ->columnSpanFull(),
                ]),

            Section::make('Kontak')
                ->description('Informasi kontak yang ditampilkan di halaman profil guru.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(30)
                            ->placeholder('(022) 1234-5678'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(150)
                            ->placeholder('nama@sman1.sch.id'),
                    ]),

                    TextInput::make('whatsapp')
                        ->label('WhatsApp')
                        ->tel()
                        ->maxLength(30)
                        ->placeholder('6281234567890')
                        ->hint('Nomor internasional tanpa + (contoh: 6281234567890).')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
