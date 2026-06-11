<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\AdmissionPath;
use App\Models\RegistrationWave;
use App\Models\SpmbRegistration;
use Illuminate\Database\Seeder;

class SpmbRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $year = AcademicYear::active() ?? AcademicYear::first();
        $paths = AdmissionPath::query()->pluck('id')->all();
        $waves = RegistrationWave::query()
            ->where('academic_year_id', $year?->id)
            ->pluck('id')
            ->all();

        if ($year === null || $paths === [] || $waves === []) {
            return;
        }

        // Data contoh — reset agar seeding dapat diulang dengan bersih.
        SpmbRegistration::query()->delete();

        SpmbRegistration::factory()
            ->count(30)
            ->sequence(fn () => [
                'academic_year_id' => $year->id,
                'registration_wave_id' => fake()->randomElement($waves),
                'admission_path_id' => fake()->randomElement($paths),
            ])
            ->create();
    }
}
