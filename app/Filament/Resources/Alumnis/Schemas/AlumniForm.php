<?php

namespace App\Filament\Resources\Alumnis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AlumniForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Data Pribadi')
                ->icon('heroicon-o-user')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(150),

                        TextInput::make('nickname')
                            ->label('Nama Panggilan')
                            ->maxLength(50),

                        TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->maxLength(100),

                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),

                        TextInput::make('phone')
                            ->label('No. HP / WhatsApp')
                            ->tel()
                            ->maxLength(30),

                        TextInput::make('occupation')
                            ->label('Pekerjaan')
                            ->maxLength(150),
                    ]),

                    Textarea::make('address')
                        ->label('Alamat')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Section::make('Data Kelulusan')
                ->icon('heroicon-o-academic-cap')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('major')
                            ->label('Jurusan')
                            ->maxLength(100),

                        TextInput::make('graduation_year')
                            ->label('Tahun Lulus')
                            ->numeric()
                            ->minValue(1950)
                            ->maxValue((int) date('Y') + 1),

                        TextInput::make('certificate_number')
                            ->label('No. Ijazah')
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->helperText('Harus unik bila diisi.'),
                    ]),
                ]),

            Section::make('Perguruan Tinggi')
                ->icon('heroicon-o-building-library')
                ->schema([
                    Toggle::make('entered_ptn')
                        ->label('Masuk PTN?')
                        ->helperText('Apakah alumni melanjutkan ke Perguruan Tinggi Negeri.')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Set $set, bool $state) => $state ? null : $set('ptn_name', null)),

                    TextInput::make('ptn_name')
                        ->label('Nama PTN')
                        ->maxLength(150)
                        ->required(fn (Get $get): bool => (bool) $get('entered_ptn'))
                        ->visible(fn (Get $get): bool => (bool) $get('entered_ptn')),
                ]),

            Section::make('Media Sosial')
                ->icon('heroicon-o-share')
                ->collapsed()
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->prefixIcon('heroicon-o-at-symbol')
                            ->maxLength(150),

                        TextInput::make('twitter')
                            ->label('Twitter / X')
                            ->prefixIcon('heroicon-o-at-symbol')
                            ->maxLength(150),

                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->maxLength(150),

                        TextInput::make('youtube')
                            ->label('Youtube Channel')
                            ->maxLength(150),
                    ]),
                ]),
        ]);
    }
}
