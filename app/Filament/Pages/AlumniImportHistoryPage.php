<?php

namespace App\Filament\Pages;

use App\Services\AlumniImportHistory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AlumniImportHistoryPage extends Page
{
    protected string $view = 'filament.pages.alumni-import-history-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Alumni';

    protected static ?string $navigationLabel = 'Riwayat Import';

    protected static ?string $title = 'Riwayat Import Alumni';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'riwayat-import-alumni';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'entries' => app(AlumniImportHistory::class)->all(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear')
                ->label('Hapus Semua Riwayat')
                ->icon(Heroicon::OutlinedTrash)
                ->color('danger')
                ->outlined()
                ->visible(fn (): bool => app(AlumniImportHistory::class)->all() !== [])
                ->requiresConfirmation()
                ->modalHeading('Hapus semua riwayat import?')
                ->modalDescription('Seluruh riwayat import akan dihapus permanen dari penyimpanan. Tindakan ini tidak dapat dibatalkan.')
                ->action(function (): void {
                    app(AlumniImportHistory::class)->clear();

                    Notification::make()
                        ->title('Riwayat import dihapus')
                        ->success()
                        ->send();
                }),
        ];
    }
}
