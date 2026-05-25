<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('email', 'admin@smamuh5.sch.id')->exists()) {
            User::factory()->create([
                'name'  => 'Administrator',
                'email' => 'admin@smamuh5.sch.id',
            ]);
        }

        $this->call(SettingSeeder::class);
        $this->call(StatSeeder::class);
        $this->call(TeacherSeeder::class);
        $this->call(SlideSeeder::class);
        $this->call(ContactItemSeeder::class);
        $this->call(StaticPageSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(TestimonialSeeder::class);
        $this->call(DownloadSeeder::class);
        $this->call(ShieldSeeder::class);
    }
}
