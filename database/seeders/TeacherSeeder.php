<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Seed sample teachers — skips if data already exists.
     */
    public function run(): void
    {
        if (Teacher::exists()) {
            return;
        }

        Teacher::factory(12)->create();
    }
}
