<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        if (Slide::exists()) {
            return;
        }

        $defaults = [
            [
                'title' => 'Unggul dalam Akademik',
                'subtitle' => 'Raih prestasi terbaik bersama guru-guru berpengalaman dan fasilitas modern.',
                'button_label' => 'Profil Sekolah',
                'button_url' => '#profil',
                'sort_order' => 1,
            ],
            [
                'title' => 'Berkarakter & Berintegritas',
                'subtitle' => 'Membentuk generasi beriman, bertakwa, dan berakhlak mulia untuk bangsa.',
                'button_label' => 'Lihat Kegiatan',
                'button_url' => '#kegiatan',
                'sort_order' => 2,
            ],
            [
                'title' => 'Pendaftaran Peserta Didik Baru',
                'subtitle' => 'SPMB resmi dibuka. Daftarkan putra-putri Anda sekarang sebelum batas waktu.',
                'button_label' => 'Daftar Sekarang',
                'button_url' => '/register',
                'sort_order' => 3,
            ],
        ];

        foreach ($defaults as $data) {
            Slide::create($data);
        }
    }
}
