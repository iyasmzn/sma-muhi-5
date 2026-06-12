<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;

class AlumniPtnPerYearChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Alumni Masuk PTN per Tahun Lulus';
    }

    protected function getData(): array
    {
        /** @var Collection<int, object{graduation_year: int, ptn: int, non_ptn: int}> $rows */
        $rows = Alumni::query()
            ->whereNotNull('graduation_year')
            ->selectRaw('graduation_year')
            ->selectRaw('SUM(CASE WHEN entered_ptn = 1 THEN 1 ELSE 0 END) as ptn')
            ->selectRaw('SUM(CASE WHEN entered_ptn = 0 THEN 1 ELSE 0 END) as non_ptn')
            ->groupBy('graduation_year')
            ->orderBy('graduation_year')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Masuk PTN',
                    'data' => $rows->pluck('ptn')->map(fn ($value): int => (int) $value)->all(),
                    'backgroundColor' => '#16a34a',
                    'borderColor' => '#16a34a',
                ],
                [
                    'label' => 'Tidak Masuk PTN',
                    'data' => $rows->pluck('non_ptn')->map(fn ($value): int => (int) $value)->all(),
                    'backgroundColor' => '#9ca3af',
                    'borderColor' => '#9ca3af',
                ],
            ],
            'labels' => $rows->pluck('graduation_year')->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['stacked' => true],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
