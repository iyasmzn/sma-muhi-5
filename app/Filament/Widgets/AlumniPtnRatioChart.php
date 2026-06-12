<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use Filament\Widgets\ChartWidget;

class AlumniPtnRatioChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = ['default' => 'full', 'md' => 1];

    public function getHeading(): ?string
    {
        return 'Alumni Masuk PTN vs Tidak';
    }

    protected function getData(): array
    {
        $enteredPtn = Alumni::query()->where('entered_ptn', true)->count();
        $notEnteredPtn = Alumni::query()->where('entered_ptn', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => [$enteredPtn, $notEnteredPtn],
                    'backgroundColor' => ['#16a34a', '#9ca3af'],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Masuk PTN', 'Tidak Masuk PTN'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
