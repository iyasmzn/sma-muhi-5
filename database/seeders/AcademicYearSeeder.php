<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\RegistrationWave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $active = AcademicYear::updateOrCreate(
            ['year_start' => 2026, 'year_end' => 2027],
            ['is_active' => true],
        );

        AcademicYear::updateOrCreate(
            ['year_start' => 2025, 'year_end' => 2026],
            ['is_active' => false],
        );

        $today = Carbon::today();

        $waves = [
            [
                'name' => 'Gelombang 1',
                'start_date' => $today->copy()->subDays(7),
                'end_date' => $today->copy()->addDays(21),
                'selection_date' => $today->copy()->addDays(26),
                'announcement_date' => $today->copy()->addDays(33),
                'is_active' => true,
            ],
            [
                'name' => 'Gelombang 2',
                'start_date' => $today->copy()->addDays(22),
                'end_date' => $today->copy()->addDays(50),
                'selection_date' => $today->copy()->addDays(55),
                'announcement_date' => $today->copy()->addDays(62),
                'is_active' => true,
            ],
        ];

        foreach ($waves as $wave) {
            RegistrationWave::updateOrCreate(
                ['academic_year_id' => $active->id, 'name' => $wave['name']],
                $wave,
            );
        }
    }
}
