<?php

namespace Tests\Feature\Filament;

use App\Filament\Widgets\AlumniPtnPerYearChart;
use App\Filament\Widgets\AlumniPtnRatioChart;
use App\Models\Alumni;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionMethod;
use Tests\TestCase;

class AlumniDashboardWidgetsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function chartData(object $widget): array
    {
        $method = new ReflectionMethod($widget, 'getData');

        return $method->invoke($widget);
    }

    public function test_ratio_chart_counts_ptn_vs_non_ptn(): void
    {
        Alumni::factory()->count(3)->enteredPtn()->create();
        Alumni::factory()->count(2)->notEnteredPtn()->create();

        $data = $this->chartData(new AlumniPtnRatioChart);

        $this->assertSame(['Masuk PTN', 'Tidak Masuk PTN'], $data['labels']);
        $this->assertSame([3, 2], $data['datasets'][0]['data']);
    }

    public function test_per_year_chart_groups_by_graduation_year(): void
    {
        Alumni::factory()->count(2)->enteredPtn()->create(['graduation_year' => 2023]);
        Alumni::factory()->count(1)->notEnteredPtn()->create(['graduation_year' => 2023]);
        Alumni::factory()->count(1)->enteredPtn()->create(['graduation_year' => 2024]);
        Alumni::factory()->count(3)->notEnteredPtn()->create(['graduation_year' => 2024]);
        // Rows without a graduation year must be excluded.
        Alumni::factory()->enteredPtn()->create(['graduation_year' => null]);

        $data = $this->chartData(new AlumniPtnPerYearChart);

        $this->assertSame([2023, 2024], $data['labels']);
        $this->assertSame('Masuk PTN', $data['datasets'][0]['label']);
        $this->assertSame([2, 1], $data['datasets'][0]['data']);
        $this->assertSame('Tidak Masuk PTN', $data['datasets'][1]['label']);
        $this->assertSame([1, 3], $data['datasets'][1]['data']);
    }
}
