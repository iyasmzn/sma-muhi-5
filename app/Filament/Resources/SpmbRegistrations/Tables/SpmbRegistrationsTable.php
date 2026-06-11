<?php

namespace App\Filament\Resources\SpmbRegistrations\Tables;

use App\Models\AcademicYear;
use App\Models\SpmbRegistration;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SpmbRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn (SpmbRegistration $record): string => $record->previous_school),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable(),

                TextColumn::make('admissionPath.name')
                    ->label('Jalur')
                    ->badge()
                    ->color(fn (SpmbRegistration $record): string => $record->admissionPath?->color ?? 'gray')
                    ->formatStateUsing(fn (?string $state, SpmbRegistration $record): string => trim(($record->admissionPath?->icon ?? '').' '.($state ?? '—')))
                    ->placeholder('—'),

                TextColumn::make('academicYear.label')
                    ->label('Tahun Ajaran')
                    ->state(fn (SpmbRegistration $record): ?string => $record->academicYear?->label)
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                TextColumn::make('registrationWave.name')
                    ->label('Gelombang')
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => SpmbRegistration::statusOptions()[$state] ?? $state),

                TextColumn::make('created_at')
                    ->label('Tgl. Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('academic_year_id')
                    ->label('Tahun Ajaran')
                    ->relationship('academicYear', 'year_start')
                    ->getOptionLabelFromRecordUsing(fn (AcademicYear $record): string => $record->label)
                    ->native(false),

                SelectFilter::make('registration_wave_id')
                    ->label('Gelombang')
                    ->relationship('registrationWave', 'name')
                    ->native(false),

                SelectFilter::make('admission_path_id')
                    ->label('Jalur')
                    ->relationship('admissionPath', 'name')
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(SpmbRegistration::statusOptions())
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make()->label('Kelola'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
