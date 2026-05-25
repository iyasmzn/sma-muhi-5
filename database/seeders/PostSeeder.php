<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        if (Post::exists()) {
            return;
        }

        $posts = [
            [
                'title' => 'Selamat Datang di Website Resmi SMA Muhammadiyah 5',
                'slug' => 'selamat-datang-di-website-resmi-sma-muhammadiyah-5',
                'excerpt' => 'Website resmi SMA Muhammadiyah 5 kini hadir untuk memudahkan akses informasi seputar kegiatan sekolah, akademik, dan penerimaan peserta didik baru.',
                'content' => '<p>Selamat datang di website resmi SMA Muhammadiyah 5! Kami dengan bangga mempersembahkan portal informasi digital yang dirancang untuk memudahkan seluruh warga sekolah, orang tua, dan masyarakat dalam mengakses berbagai informasi penting.</p><h2>Fitur Unggulan Website</h2><p>Melalui website ini, Anda dapat menemukan berbagai informasi meliputi profil sekolah, kegiatan akademik dan ekstrakurikuler, pengumuman penting, serta formulir pendaftaran peserta didik baru secara online.</p><p>Kami berkomitmen untuk terus memperbarui konten website ini agar selalu relevan dan informatif bagi seluruh pengunjung. Jangan ragu untuk menghubungi kami jika ada pertanyaan atau masukan.</p>',
                'category' => 'Berita',
                'author' => 'Tim Humas',
                'author_initials' => 'TH',
                'read_time' => 2,
                'is_published' => true,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'SPMB 2026/2027 Resmi Dibuka — Ini Jalur dan Syaratnya',
                'slug' => 'spmb-2026-2027-resmi-dibuka-jalur-dan-syarat',
                'excerpt' => 'Penerimaan Peserta Didik Baru tahun ajaran 2026/2027 resmi dibuka. Tersedia 4 jalur pendaftaran: Zonasi, Prestasi, Afirmasi, dan Mutasi.',
                'content' => '<p>SMA Muhammadiyah 5 dengan bangga mengumumkan pembukaan resmi Seleksi Penerimaan Murid Baru (SPMB) untuk Tahun Ajaran 2026/2027. Pendaftaran dibuka mulai hari ini hingga 30 Mei 2026.</p><h2>Jalur Pendaftaran</h2><p>Terdapat empat jalur pendaftaran yang tersedia bagi calon peserta didik baru:</p><ul><li><strong>Jalur Zonasi</strong> — Diperuntukkan bagi calon peserta didik yang berdomisili di sekitar sekolah.</li><li><strong>Jalur Prestasi</strong> — Berdasarkan nilai rapor atau prestasi akademik/non-akademik di tingkat kabupaten hingga internasional.</li><li><strong>Jalur Afirmasi</strong> — Bagi peserta didik dari keluarga tidak mampu yang dibuktikan dengan DTKS atau kartu sosial lainnya.</li><li><strong>Jalur Mutasi</strong> — Untuk anak tenaga kependidikan atau orang tua yang pindah tugas.</li></ul><h2>Persyaratan Umum</h2><p>Calon peserta didik diwajibkan menyiapkan dokumen berikut: ijazah/SKL, rapor kelas 7–9, akta kelahiran, kartu keluarga, dan pas foto terbaru.</p><p>Pendaftaran dilakukan secara online melalui halaman PPDB di website ini. Untuk informasi lebih lanjut, hubungi panitia SPMB melalui WhatsApp resmi sekolah.</p>',
                'category' => 'Pengumuman',
                'author' => 'Panitia SPMB',
                'author_initials' => 'PS',
                'read_time' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Siswa SMA Muhammadiyah 5 Raih Juara 1 Olimpiade Matematika Tingkat Provinsi',
                'slug' => 'siswa-raih-juara-1-olimpiade-matematika-tingkat-provinsi',
                'excerpt' => 'Kebanggaan bagi SMA Muhammadiyah 5! Salah satu siswa kelas XI berhasil meraih juara pertama pada Olimpiade Matematika Tingkat Provinsi Jawa Barat.',
                'content' => '<p>SMA Muhammadiyah 5 kembali menorehkan prestasi membanggakan di kancah akademik. Pada Olimpiade Matematika Tingkat Provinsi Jawa Barat yang diselenggarakan pekan lalu, salah satu siswa terbaik kami berhasil meraih posisi juara pertama.</p><h2>Perjuangan Panjang Meraih Prestasi</h2><p>Prestasi ini bukan diraih dalam semalam. Selama hampir enam bulan, siswa tersebut menjalani pembinaan intensif bersama guru pembimbing. Latihan soal, diskusi kelompok, dan simulasi olimpiade menjadi rutinitas harian yang tak pernah terlewat.</p><p>Kepala sekolah menyampaikan rasa bangga atas pencapaian luar biasa ini. "Ini adalah bukti nyata bahwa kerja keras dan bimbingan yang tepat dapat menghasilkan prestasi terbaik," ujarnya.</p><h2>Langkah Selanjutnya</h2><p>Dengan prestasi di tingkat provinsi ini, siswa tersebut akan melanjutkan perjalanan ke Olimpiade Sains Nasional (OSN) yang akan diselenggarakan dalam waktu dekat. Seluruh civitas akademika sekolah memberikan dukungan penuh.</p>',
                'category' => 'Prestasi',
                'author' => 'Ahmad Fauzi, M.Pd.',
                'author_initials' => 'AF',
                'read_time' => 3,
                'is_published' => true,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS) Tahun Ajaran Baru',
                'slug' => 'kegiatan-masa-pengenalan-lingkungan-sekolah-mpls',
                'excerpt' => 'MPLS untuk siswa baru tahun ajaran 2025/2026 berlangsung meriah selama tiga hari. Peserta diperkenalkan dengan seluruh fasilitas, kurikulum, dan budaya sekolah.',
                'content' => '<p>Masa Pengenalan Lingkungan Sekolah (MPLS) tahun ajaran 2025/2026 telah sukses diselenggarakan selama tiga hari di SMA Muhammadiyah 5. Kegiatan ini dirancang untuk membantu siswa baru beradaptasi dengan lingkungan sekolah yang baru.</p><h2>Rangkaian Kegiatan MPLS</h2><p>Selama tiga hari pelaksanaan, para siswa baru mengikuti berbagai kegiatan positif yang dipandu oleh panitia OSIS dan pembina sekolah. Kegiatan meliputi pengenalan fasilitas sekolah, diskusi kurikulum, sesi motivasi, serta pengenalan organisasi dan ekstrakurikuler yang tersedia.</p><p>Selain itu, siswa juga mengikuti sesi tentang tata tertib sekolah, budaya belajar yang efektif, serta pengenalan teknologi informasi yang digunakan dalam proses pembelajaran.</p><h2>Antusias dan Semangat Siswa Baru</h2><p>Seluruh siswa baru terlihat antusias dan bersemangat mengikuti setiap sesi kegiatan. Banyak dari mereka yang sudah mulai menjalin persahabatan baru dan memilih kegiatan ekstrakurikuler yang diminati.</p>',
                'category' => 'Akademik',
                'author' => 'Siti Rahayu, S.Pd.',
                'author_initials' => 'SR',
                'read_time' => 3,
                'is_published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Program Beasiswa Berprestasi — Daftar Sekarang Sebelum Batas Waktu',
                'slug' => 'program-beasiswa-berprestasi-daftar-sekarang',
                'excerpt' => 'SMA Muhammadiyah 5 membuka pendaftaran beasiswa bagi calon siswa berprestasi. Beasiswa mencakup pembebasan biaya sekolah selama 3 tahun.',
                'content' => '<p>Dalam rangka mendukung akses pendidikan yang berkualitas, SMA Muhammadiyah 5 kembali membuka program beasiswa bagi calon peserta didik berprestasi. Program ini merupakan wujud komitmen sekolah dalam mencetak generasi unggul dari berbagai latar belakang.</p><h2>Cakupan Beasiswa</h2><p>Beasiswa yang diberikan mencakup pembebasan biaya SPP selama tiga tahun masa studi, tunjangan buku dan alat tulis setiap semester, serta akses prioritas ke program pembinaan olimpiade dan kompetisi akademik.</p><h2>Persyaratan Pendaftar</h2><p>Calon penerima beasiswa harus memenuhi kriteria berikut:</p><ul><li>Nilai rapor rata-rata minimal 85 dari kelas 7 hingga 9</li><li>Memiliki prestasi akademik atau non-akademik di tingkat minimal kabupaten/kota</li><li>Surat rekomendasi dari kepala sekolah asal</li><li>Mengikuti tes seleksi yang diselenggarakan pihak sekolah</li></ul><p>Pendaftaran dibuka hingga 25 Mei 2026. Informasi lebih lanjut dapat diperoleh melalui bagian kesiswaan atau menghubungi WhatsApp resmi sekolah.</p>',
                'category' => 'Akademik',
                'author' => 'Dewi Lestari',
                'author_initials' => 'DL',
                'read_time' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Ekstrakurikuler Robotika SMA Muhammadiyah 5 Tampil di Pameran Teknologi',
                'slug' => 'ekstrakurikuler-robotika-tampil-di-pameran-teknologi',
                'excerpt' => 'Tim robotika sekolah berhasil unjuk gigi di Pameran Teknologi Pendidikan Regional. Karya mereka mendapat apresiasi tinggi dari dewan juri dan pengunjung.',
                'content' => '<p>Kebanggaan kembali datang dari SMA Muhammadiyah 5. Kali ini, tim ekstrakurikuler Robotika berhasil menampilkan karya inovatif mereka di Pameran Teknologi Pendidikan Regional yang diikuti lebih dari 30 sekolah se-Jawa Barat.</p><h2>Karya Robot Penjelajah Sampah Otomatis</h2><p>Tim yang terdiri dari 6 siswa kelas X dan XI ini menampilkan robot penjelajah yang mampu mendeteksi dan mengumpulkan sampah secara otomatis menggunakan sensor ultrasonik dan kecerdasan buatan berbasis Arduino.</p><p>Karya ini mendapat sambutan luar biasa dari para pengunjung dan dewan juri yang terdiri dari akademisi dan praktisi teknologi. Mereka menilai inovasi ini tidak hanya kreatif, tetapi juga memiliki nilai guna yang tinggi bagi lingkungan.</p><h2>Proses Panjang di Balik Karya</h2><p>Dibutuhkan waktu lebih dari empat bulan untuk merancang, membangun, dan menyempurnakan robot ini. Pembimbing ekstrakurikuler mengungkapkan bahwa proses pengembangan diwarnai banyak percobaan dan kegagalan sebelum akhirnya menghasilkan karya yang membanggakan.</p>',
                'category' => 'Teknologi',
                'author' => 'Budi Santoso',
                'author_initials' => 'BS',
                'read_time' => 5,
                'is_published' => true,
                'published_at' => now()->subDays(3),
            ],
        ];

        foreach ($posts as $data) {
            Post::create($data);
        }
    }
}
