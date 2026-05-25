<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section class="mb-12 border-t pt-12" style="border-color:var(--border)">
        <div class="text-center mb-10" data-aos="fade-up">
            <div class="fi-label mb-2">Cara Mendaftar</div>
            <h2 class="text-2xl font-bold" style="color:var(--text)">Tahapan SPMB {{ setting('spmb_year', '2026/2027') }}</h2>
            <p class="mt-2 text-sm" style="color:var(--muted)">Ikuti langkah-langkah berikut untuk mendaftarkan diri sebagai calon peserta didik baru.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['01', '📝', 'Isi Formulir', 'Isi formulir pendaftaran online secara lengkap dan benar melalui portal SPMB.'],
                ['02', '📁', 'Upload Berkas', 'Upload dokumen yang dipersyaratkan: ijazah/SHUN, rapor, dan pas foto terbaru.'],
                ['03', '✅', 'Verifikasi', 'Berkas diverifikasi oleh panitia. Pantau status pendaftaran melalui akun Anda.'],
                ['04', '🎉', 'Pengumuman', 'Hasil seleksi diumumkan pada tanggal ' . setting('spmb_announce', '25 Juni') . ' melalui portal resmi sekolah.'],
            ] as [$num, $icon, $title, $desc])
                <div class="fi-card fi-card-hover p-6 relative"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute top-4 right-4 text-3xl font-black text-amber-100 select-none">{{ $num }}</div>
                    <div class="text-2xl mb-3">{{ $icon }}</div>
                    <div class="font-bold text-sm mb-2" style="color:var(--text)">{{ $title }}</div>
                    <p class="text-xs leading-relaxed" style="color:var(--muted)">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 text-center">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary">
                    Mulai Pendaftaran
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @endif
        </div>
    </section>
</div>
