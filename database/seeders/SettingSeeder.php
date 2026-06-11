<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed semua settings default aplikasi.
     * Menggunakan firstOrCreate agar tidak menimpa data yang sudah diubah admin.
     */
    public function run(): void
    {
        $defaults = $this->defaults();

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    /** @return array<string, mixed> */
    private function defaults(): array
    {
        return array_merge(
            $this->general(),
            $this->navbar(),
            $this->landingPage(),
            $this->principal(),
            $this->quickLinks(),
            $this->spmb(),
            $this->theme(),
            $this->errorPages(),
        );
    }

    // ── Pengaturan Umum ──────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function general(): array
    {
        return [
            'site_name' => 'SMA Muhammadiyah 5',
            'site_tagline' => 'Unggul, Berkarakter, Berprestasi',
            'site_description' => 'Website resmi SMA Muhammadiyah 5. Informasi SPMB, akademik, kegiatan, dan berita sekolah.',

            'contact_address' => 'Jl. Pendidikan No. 1, Kota Bandung 40111',
            'contact_phone' => '(022) 1234-5678',
            'contact_email' => 'info@smamuh5.sch.id',
            'contact_hours' => 'Senin–Jumat, 07.00–15.30 WIB',
            'contact_map_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63370.996079930104!2d107.56067055291257!3d-6.927935776306638!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e64c5e8866e5%3A0x37be7ac9d575f7ed!2sGedung%20Sate!5e0!3m2!1sid!2sid!4v1779677079761!5m2!1sid!2sid',

            'social_facebook' => null,
            'social_instagram' => null,
            'social_youtube' => null,
            'social_whatsapp' => null,
        ];
    }

    // ── Menu Navigasi ────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function navbar(): array
    {
        return [
            'nav_items' => json_encode([
                ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Profil',   'url' => '#profil',   'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'SPMB',     'url' => '#spmb',     'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Akademik', 'url' => '#akademik', 'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true, 'children' => []],
                ['label' => 'Kontak',   'url' => '#kontak',   'target' => '_self', 'is_active' => true, 'children' => []],
            ]),
        ];
    }

    // ── Urutan Seksi Halaman Depan ───────────────────────────────────

    /** @return array<string, mixed> */
    private function landingPage(): array
    {
        return [
            'section_order' => json_encode([
                ['key' => 'section_hero',        'visible' => true],
                ['key' => 'section_quick_links', 'visible' => true],
                ['key' => 'section_spmb',        'visible' => true],
                ['key' => 'section_stats',       'visible' => true],
                ['key' => 'section_principal',   'visible' => true],
                ['key' => 'section_spmb_steps',  'visible' => true],
                ['key' => 'section_activities',  'visible' => true],
                ['key' => 'section_gallery',     'visible' => true],
                ['key' => 'section_blog',        'visible' => true],
                ['key' => 'section_contact',     'visible' => true],
            ]),
        ];
    }

    // ── Kepala Sekolah ───────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function principal(): array
    {
        return [
            'principal_name' => 'Drs. Ahmad Fauzi, M.Pd.',
            'principal_nip' => '197601012005011001',
            'principal_title' => 'Kepala Sekolah',
            'principal_photo' => null,
            'principal_excerpt' => 'Kami berkomitmen memberikan pendidikan terbaik untuk mencetak generasi yang beriman, berilmu, dan berdaya saing tinggi. Bersama seluruh warga sekolah, kami terus berinovasi demi masa depan peserta didik yang lebih gemilang.',
            'principal_page' => 'sambutan-kepala-sekolah',
        ];
    }

    // ── Tautan Cepat ─────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function quickLinks(): array
    {
        return [
            'quick_links' => json_encode([
                ['icon' => '📋', 'label' => 'SPMB',    'url' => '#spmb',    'is_active' => true],
                ['icon' => '📥', 'label' => 'Unduhan', 'url' => '/unduhan', 'is_active' => true],
                ['icon' => '📅', 'label' => 'Jadwal',  'url' => '#jadwal',  'is_active' => true],
                ['icon' => '🏆', 'label' => 'Prestasi', 'url' => '#prestasi', 'is_active' => true],
                ['icon' => '👥', 'label' => 'Alumni',  'url' => '#alumni',  'is_active' => true],
                ['icon' => '📞', 'label' => 'Kontak',  'url' => '#kontak',  'is_active' => true],
            ]),
        ];
    }

    // ── PPDB / SPMB ──────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function spmb(): array
    {
        $procedures = [
            ['icon' => '📝', 'title' => 'Isi Formulir Online',  'description' => 'Kunjungi halaman PPDB dan isi formulir pendaftaran secara lengkap dan benar melalui portal SPMB.'],
            ['icon' => '📁', 'title' => 'Siapkan Berkas',       'description' => 'Persiapkan dokumen yang dipersyaratkan: ijazah/SHUN, rapor kelas 7–9, dan pas foto terbaru.'],
            ['icon' => '✅', 'title' => 'Verifikasi Berkas',    'description' => 'Datang ke sekolah untuk verifikasi berkas pada tanggal yang telah ditentukan oleh panitia.'],
            ['icon' => '🎉', 'title' => 'Pengumuman Hasil',     'description' => 'Hasil seleksi diumumkan melalui halaman resmi sekolah dan notifikasi WhatsApp/email.'],
        ];

        $fees = [
            ['category' => 'Biaya Pendaftaran', 'amount' => 'Rp 0',       'note' => 'Gratis'],
            ['category' => 'Seragam Sekolah',   'amount' => 'Rp 500.000', 'note' => '3 stel seragam (OSIS, Pramuka, Olahraga)'],
            ['category' => 'Buku Paket',        'amount' => 'Rp 350.000', 'note' => 'Seluruh mata pelajaran, per semester'],
            ['category' => 'Kegiatan MOS/MPLS', 'amount' => 'Rp 150.000', 'note' => 'Masa Pengenalan Lingkungan Sekolah'],
        ];

        return [
            // Jadwal & status
            'spmb_year' => '2026/2027',
            'spmb_open' => 1,
            'spmb_deadline' => '30 Mei',
            'spmb_select' => '10 Juni',
            'spmb_announce' => '25 Juni',

            // Kartu di halaman depan
            'spmb_card_title' => 'SPMB Tahun Ajaran {year} Dibuka!',
            'spmb_card_description' => 'Pendaftaran peserta didik baru resmi dibuka. Tersedia jalur Prestasi, Zonasi, dan Afirmasi. Segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu.',
            'spmb_card_cta_label' => 'Daftar Sekarang',
            'spmb_card_cta_url' => '/ppdb',
            'spmb_card_secondary_label' => 'Info Selengkapnya',

            // Section tahapan di halaman depan
            'spmb_steps_title' => 'Tahapan SPMB',
            'spmb_steps_description' => 'Ikuti langkah-langkah berikut untuk mendaftarkan diri sebagai calon peserta didik baru.',
            'spmb_steps_cta_label' => 'Lihat Detail & Daftar',
            'spmb_steps_cta_url' => '/ppdb',

            // Form pendaftaran
            'spmb_form_enabled' => 1,
            'spmb_form_title' => 'Formulir Pendaftaran SPMB',
            'spmb_form_description' => 'Isi formulir di bawah ini dengan data yang benar dan lengkap. Panitia akan menghubungi Anda untuk proses verifikasi.',
            'spmb_closed_message' => 'Pendaftaran SPMB saat ini sedang ditutup. Pantau informasi terbaru melalui halaman ini atau hubungi panitia via WhatsApp.',

            // Konten prosedur & biaya
            'spmb_procedures' => json_encode($procedures),
            'spmb_fees' => json_encode($fees),
        ];
    }

    // ── Tema & Tampilan ───────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function theme(): array
    {
        return [
            'theme_primary_color' => '#d97706',
            'theme_font' => 'instrument-sans',
        ];
    }

    // ── Halaman Error ─────────────────────────────────────────────────

    /** @return array<string, mixed> */
    private function errorPages(): array
    {
        return [
            'error_403_label' => 'Akses Ditolak',
            'error_403_title' => 'Kamu tidak punya akses',
            'error_403_message' => 'Halaman ini bersifat terbatas. Jika kamu merasa ini sebuah kesalahan, silakan hubungi administrator.',

            'error_404_label' => 'Halaman Tidak Ditemukan',
            'error_404_title' => 'Sepertinya kamu tersesat',
            'error_404_message' => 'Halaman yang kamu cari mungkin sudah dipindahkan, dihapus, atau alamatnya salah ketik.',

            'error_419_label' => 'Sesi Berakhir',
            'error_419_title' => 'Sesi kamu telah berakhir',
            'error_419_message' => 'Demi keamanan, sesi kamu telah kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.',

            'error_429_label' => 'Terlalu Banyak Permintaan',
            'error_429_title' => 'Pelan-pelan dulu',
            'error_429_message' => 'Kamu mengirim terlalu banyak permintaan dalam waktu singkat. Mohon tunggu beberapa saat lalu coba lagi.',

            'error_500_label' => 'Kesalahan Server',
            'error_500_title' => 'Ada yang tidak beres',
            'error_500_message' => 'Terjadi kesalahan di server kami. Tim kami sudah diberi tahu dan sedang menanganinya.',

            'error_503_label' => 'Sedang Pemeliharaan',
            'error_503_title' => 'Website sedang dalam pemeliharaan',
            'error_503_message' => 'Kami sedang melakukan perbaikan agar layanan menjadi lebih baik. Silakan kembali beberapa saat lagi.',
        ];
    }
}
