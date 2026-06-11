<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@smamuh5.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call(SettingSeeder::class);
        $this->call(AdmissionPathSeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(SpmbRegistrationSeeder::class);
        $this->call(StatSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(StaticPageSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(ProgramSeeder::class);
        $this->call(DownloadSeeder::class);
        $this->call(ShieldSeeder::class);
    }
}
