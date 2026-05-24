<?php

namespace Database\Seeders;

use App\Models\Stat;
use Illuminate\Database\Seeder;

class StatSeeder extends Seeder
{
    /**
     * Seed the four default landing-page stat cards.
     */
    public function run(): void
    {
        $defaults = [
            ['icon' => '🏫', 'label' => 'Berdiri Sejak',    'value' => '1985',  'sub' => 'Terakreditasi A',    'sort_order' => 1],
            ['icon' => '🎓', 'label' => 'Total Siswa',      'value' => '1.240', 'sub' => 'Aktif tahun ini',    'sort_order' => 2],
            ['icon' => '👨‍🏫', 'label' => 'Tenaga Pendidik', 'value' => '86',    'sub' => 'Guru bersertifikat', 'sort_order' => 3],
            ['icon' => '🏆', 'label' => 'Prestasi',         'value' => '200+',  'sub' => 'Tingkat nasional',   'sort_order' => 4],
        ];

        foreach ($defaults as $data) {
            Stat::firstOrCreate(['label' => $data['label']], $data);
        }
    }
}
