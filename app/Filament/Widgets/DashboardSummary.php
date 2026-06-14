<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use App\Models\Post;
use App\Models\SpmbRegistration;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardSummary extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $totalRegistrations = SpmbRegistration::count();
        $pendingRegistrations = SpmbRegistration::pending()->count();

        $totalAlumni = Alumni::count();
        $alumniEnteredPtn = Alumni::enteredPtn()->count();

        return [
            Stat::make('Pendaftar SPMB', number_format($totalRegistrations))
                ->description("{$pendingRegistrations} menunggu verifikasi")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            Stat::make('Alumni', number_format($totalAlumni))
                ->description("{$alumniEnteredPtn} diterima di PTN")
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),
            Stat::make('Berita Terbit', number_format(Post::published()->count()))
                ->description('Artikel & berita aktif')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('info'),
            Stat::make('Guru & Tendik', number_format(Teacher::count()))
                ->description('Total tenaga pendidik')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
