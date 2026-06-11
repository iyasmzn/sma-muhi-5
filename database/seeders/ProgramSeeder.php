<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var array<array{title:string,category:string,icon:string,excerpt:string,is_featured:bool}> */
        $programs = [
            [
                'title' => 'Tahfizh Al-Qur\'an',
                'category' => 'Keagamaan',
                'icon' => '🕌',
                'excerpt' => 'Program unggulan menghafal Al-Qur\'an dengan bimbingan ustadz/ustadzah berpengalaman dan target hafalan terukur.',
                'is_featured' => true,
            ],
            [
                'title' => 'Kelas Sains & Olimpiade',
                'category' => 'Akademik',
                'icon' => '🔬',
                'excerpt' => 'Pembinaan intensif menuju olimpiade sains nasional dan internasional di bidang Matematika, Fisika, Kimia, dan Biologi.',
                'is_featured' => true,
            ],
            [
                'title' => 'Literasi Digital & Coding',
                'category' => 'Teknologi',
                'icon' => '💻',
                'excerpt' => 'Membekali siswa kemampuan pemrograman, desain, dan literasi digital untuk siap menghadapi era teknologi.',
                'is_featured' => true,
            ],
            [
                'title' => 'Bahasa Asing (Arab & Inggris)',
                'category' => 'Akademik',
                'icon' => '🗣️',
                'excerpt' => 'Penguatan kemampuan berbahasa Arab dan Inggris aktif melalui kelas, halaqah, dan English/Arabic day.',
                'is_featured' => true,
            ],
            [
                'title' => 'Ekstrakurikuler Olahraga',
                'category' => 'Ekstrakurikuler',
                'icon' => '⚽',
                'excerpt' => 'Beragam cabang olahraga seperti futsal, basket, panahan, dan bela diri untuk membentuk fisik dan sportivitas.',
                'is_featured' => true,
            ],
            [
                'title' => 'Pembinaan Karakter & Adab',
                'category' => 'Karakter',
                'icon' => '🌱',
                'excerpt' => 'Penanaman nilai akhlak mulia, kedisiplinan, dan kepemimpinan melalui pembiasaan harian dan mentoring.',
                'is_featured' => true,
            ],
            [
                'title' => 'Seni & Kaligrafi',
                'category' => 'Ekstrakurikuler',
                'icon' => '🎨',
                'excerpt' => 'Mengembangkan bakat seni rupa, musik islami, dan kaligrafi sebagai wadah ekspresi dan kreativitas siswa.',
                'is_featured' => false,
            ],
            [
                'title' => 'Pramuka & Kepemimpinan',
                'category' => 'Ekstrakurikuler',
                'icon' => '🎯',
                'excerpt' => 'Kegiatan kepramukaan untuk melatih kemandirian, kerja sama tim, dan jiwa kepemimpinan siswa.',
                'is_featured' => false,
            ],
        ];

        foreach ($programs as $index => $data) {
            Program::firstOrCreate(
                ['slug' => Str::slug($data['title'])],
                [
                    'title' => $data['title'],
                    'category' => $data['category'],
                    'icon' => $data['icon'],
                    'excerpt' => $data['excerpt'],
                    'description' => '<p>'.$data['excerpt'].'</p><h2>Tujuan Program</h2><p>Program ini dirancang untuk mendukung perkembangan potensi siswa secara menyeluruh, baik dari sisi akademik, keterampilan, maupun karakter.</p>',
                    'is_featured' => $data['is_featured'],
                    'is_published' => true,
                    'sort_order' => $index,
                ]
            );
        }
    }
}
