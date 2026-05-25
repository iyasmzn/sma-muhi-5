@php
    $spmbYear    = setting('spmb_year', '2026/2027');
    $stepsTitle  = setting('spmb_steps_title', 'Tahapan SPMB');
    $stepsDesc   = setting('spmb_steps_description', 'Ikuti langkah-langkah berikut untuk mendaftarkan diri sebagai calon peserta didik baru.');
    $ctaLabel    = setting('spmb_steps_cta_label', 'Lihat Detail & Daftar');
    $ctaUrl      = setting('spmb_steps_cta_url', '/ppdb');

    $procedures = json_decode(setting('spmb_procedures', ''), true) ?: [
        ['icon' => '📝', 'title' => 'Isi Formulir Online', 'description' => 'Kunjungi halaman PPDB dan isi formulir pendaftaran secara lengkap dan benar melalui portal SPMB.'],
        ['icon' => '📁', 'title' => 'Siapkan Berkas', 'description' => 'Persiapkan dokumen yang dipersyaratkan: ijazah/SHUN, rapor, dan pas foto terbaru.'],
        ['icon' => '✅', 'title' => 'Verifikasi Berkas', 'description' => 'Berkas diverifikasi oleh panitia. Pantau status pendaftaran melalui akun Anda.'],
        ['icon' => '🎉', 'title' => 'Pengumuman', 'description' => 'Hasil seleksi diumumkan pada tanggal ' . setting('spmb_announce', '25 Juni') . ' melalui portal resmi sekolah.'],
    ];

    // Tampilkan maks 4 langkah di halaman depan
    $stepsPreview = array_slice($procedures, 0, 4);
    $hasMore      = count($procedures) > 4;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section class="mb-12 border-t pt-12" style="border-color:var(--border)">
        <div class="text-center mb-10" data-aos="fade-up">
            <div class="fi-label mb-2">Cara Mendaftar</div>
            <h2 class="text-2xl font-bold" style="color:var(--text)">{{ $stepsTitle }} {{ $spmbYear }}</h2>
            <p class="mt-2 text-sm" style="color:var(--muted)">{{ $stepsDesc }}</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($stepsPreview as $index => $step)
                <div class="fi-card fi-card-hover p-6 relative"
                     data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="absolute top-4 right-4 text-3xl font-black text-amber-100 select-none">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-2xl mb-3">{{ $step['icon'] ?? '📌' }}</div>
                    <div class="font-bold text-sm mb-2" style="color:var(--text)">{{ $step['title'] ?? '' }}</div>
                    <p class="text-xs leading-relaxed" style="color:var(--muted)">{{ $step['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3" data-aos="fade-up">
            <a href="{{ $ctaUrl }}" class="btn-primary">
                {{ $ctaLabel }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            @if($hasMore)
                <a href="{{ route('ppdb.index') }}" class="btn-outline text-sm">
                    Lihat {{ count($procedures) - 4 }} langkah lainnya
                </a>
            @endif
        </div>
    </section>
</div>
