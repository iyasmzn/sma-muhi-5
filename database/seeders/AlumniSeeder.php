<?php

namespace Database\Seeders;

use App\Models\Alumni;
use Illuminate\Database\Seeder;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        if (Alumni::exists()) {
            return;
        }

        $currentYear = (int) date('Y');

        // Sebar lulusan ke 7 tahun terakhir dengan rasio PTN yang bervariasi
        // agar grafik per-tahun dan pie chart terlihat berisi.
        for ($year = $currentYear - 6; $year <= $currentYear; $year++) {
            $total = fake()->numberBetween(12, 25);
            $enteredPtn = (int) round($total * fake()->randomFloat(2, 0.4, 0.7));

            Alumni::factory()
                ->count($enteredPtn)
                ->enteredPtn()
                ->create(['graduation_year' => $year]);

            Alumni::factory()
                ->count($total - $enteredPtn)
                ->notEnteredPtn()
                ->create(['graduation_year' => $year]);
        }
    }
}
