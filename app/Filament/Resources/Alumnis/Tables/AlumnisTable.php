<?php

namespace App\Filament\Resources\Alumnis\Tables;

use App\Models\Alumni;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AlumnisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): ?string => $record->occupation),

                TextColumn::make('major')
                    ->label('Jurusan')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),

                TextColumn::make('graduation_year')
                    ->label('Tahun Lulus')
                    ->sortable()
                    ->placeholder('—'),

                TextColumn::make('certificate_number')
                    ->label('No. Ijazah')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('phone')
                    ->label('No. HP')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('—'),

                IconColumn::make('entered_ptn')
                    ->label('PTN')
                    ->boolean(),

                TextColumn::make('ptn_name')
                    ->label('Nama PTN')
                    ->toggleable()
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Ditambahkan')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('full_name')
            ->filters([
                SelectFilter::make('major')
                    ->label('Jurusan')
                    ->options(fn (): array => self::distinctMajors())
                    ->native(false),

                SelectFilter::make('graduation_year')
                    ->label('Tahun Lulus')
                    ->options(fn (): array => self::distinctGraduationYears())
                    ->native(false),

                TernaryFilter::make('entered_ptn')
                    ->label('Masuk PTN')
                    ->placeholder('Semua')
                    ->trueLabel('Masuk PTN')
                    ->falseLabel('Tidak masuk PTN'),

                Filter::make('without_certificate')
                    ->label('Belum punya No. Ijazah')
                    ->query(fn (Builder $query): Builder => $query->whereNull('certificate_number')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return array<string, string>
     */
    protected static function distinctMajors(): array
    {
        return Alumni::query()
            ->whereNotNull('major')
            ->distinct()
            ->orderBy('major')
            ->pluck('major', 'major')
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected static function distinctGraduationYears(): array
    {
        return Alumni::query()
            ->whereNotNull('graduation_year')
            ->distinct()
            ->orderByDesc('graduation_year')
            ->pluck('graduation_year', 'graduation_year')
            ->all();
    }
}
