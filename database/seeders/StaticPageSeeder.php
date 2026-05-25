<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Seed halaman-halaman statis default sekolah.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Informasi Umum',
                'slug' => 'informasi-umum',
                'meta_description' => 'Informasi umum mengenai sekolah kami, sejarah, dan profil singkat lembaga pendidikan.',
                'content' => '<h2>Informasi Umum</h2><p>Selamat datang di halaman informasi umum sekolah kami. Di sini Anda dapat menemukan berbagai informasi dasar mengenai profil sekolah, sejarah berdiri, dan perkembangan lembaga dari masa ke masa.</p><p>Sekolah kami berdiri sejak tahun 1985 dan telah mencetak ribuan lulusan yang berkiprah di berbagai bidang. Dengan fasilitas modern dan tenaga pengajar berpengalaman, kami berkomitmen memberikan pendidikan terbaik bagi seluruh peserta didik.</p>',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Sambutan Kepala Sekolah',
                'slug' => 'sambutan-kepala-sekolah',
                'meta_description' => 'Sambutan dan pesan dari Kepala Sekolah untuk seluruh warga sekolah dan masyarakat.',
                'content' => '<h2>Sambutan Kepala Sekolah</h2><p>Assalamualaikum Warahmatullahi Wabarakatuh,</p><p>Puji syukur kami panjatkan ke hadirat Allah SWT atas segala rahmat dan hidayah-Nya sehingga sekolah kami terus tumbuh dan berkembang menjadi lembaga pendidikan yang semakin berkualitas.</p><p>Sebagai kepala sekolah, saya mengucapkan selamat datang kepada seluruh peserta didik baru, orang tua/wali murid, dan masyarakat yang telah mempercayakan pendidikan putra-putri terbaik kepada kami. Kepercayaan Anda adalah amanah yang kami emban dengan penuh tanggung jawab.</p><p>Kami terus berbenah dan berinovasi agar dapat memberikan layanan pendidikan terbaik yang menghasilkan lulusan berakhlak mulia, cerdas, dan berdaya saing tinggi.</p><p>Wassalamualaikum Warahmatullahi Wabarakatuh.</p><p><strong>Kepala Sekolah</strong></p>',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Visi & Misi',
                'slug' => 'visi-misi',
                'meta_description' => 'Visi dan misi sekolah sebagai panduan dalam penyelenggaraan pendidikan yang bermutu.',
                'content' => '<h2>Visi</h2><p>Menjadi lembaga pendidikan unggulan yang menghasilkan generasi beriman, berilmu, berkarakter, dan berdaya saing di era global.</p><h2>Misi</h2><ul><li>Menyelenggarakan pembelajaran berkualitas yang inovatif dan berpusat pada peserta didik.</li><li>Membentuk karakter peserta didik yang berakhlak mulia, disiplin, dan bertanggung jawab.</li><li>Mengembangkan potensi akademik dan non-akademik setiap peserta didik secara optimal.</li><li>Membangun budaya sekolah yang kondusif, inklusif, dan berwawasan lingkungan.</li><li>Menjalin kemitraan yang harmonis dengan orang tua, masyarakat, dan dunia usaha/industri.</li></ul><h2>Tujuan</h2><p>Mewujudkan peserta didik yang mampu berpikir kritis, kreatif, dan kolaboratif sehingga siap menghadapi tantangan abad ke-21 dengan berlandaskan nilai-nilai luhur bangsa.</p>',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Informasi Lokasi Sekolah',
                'slug' => 'informasi-lokasi-sekolah',
                'meta_description' => 'Alamat, peta lokasi, dan informasi cara menuju sekolah kami.',
                'content' => '<h2>Lokasi Sekolah</h2><p>Sekolah kami berlokasi di pusat kota yang mudah dijangkau dengan berbagai moda transportasi umum maupun pribadi.</p><h3>Alamat Lengkap</h3><p>Jl. Pendidikan No. 1, Kelurahan Maju Jaya, Kecamatan Sejahtera, Kota Harapan, Provinsi Indonesia 12345</p><h3>Kontak</h3><ul><li><strong>Telepon:</strong> (021) 1234-5678</li><li><strong>Fax:</strong> (021) 1234-5679</li><li><strong>Email:</strong> info@sekolah.sch.id</li></ul><h3>Jam Operasional</h3><ul><li>Senin – Kamis: 07.00 – 15.00 WIB</li><li>Jumat: 07.00 – 11.30 WIB</li><li>Sabtu – Minggu: Tutup</li></ul><h3>Petunjuk Arah</h3><p>Dari pusat kota, ikuti Jl. Utama ke arah utara ± 2 km, kemudian belok kiri di perempatan lampu merah menuju Jl. Pendidikan. Sekolah terletak di sebelah kanan jalan, bersebelahan dengan Taman Kota.</p>',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($pages as $data) {
            StaticPage::firstOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }
}
