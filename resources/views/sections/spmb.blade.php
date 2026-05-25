@php
    $spmbYear      = setting('spmb_year', '2026/2027');
    $cardTitle     = setting('spmb_card_title', 'SPMB Tahun Ajaran {year} Dibuka!');
    $cardTitle     = str_replace('{year}', $spmbYear, $cardTitle);
    $cardDesc      = setting('spmb_card_description', 'Pendaftaran peserta didik baru resmi dibuka. Tersedia jalur Prestasi, Zonasi, dan Afirmasi. Segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu.');
    $ctaLabel      = setting('spmb_card_cta_label', 'Daftar Sekarang');
    $ctaUrl        = setting('spmb_card_cta_url', '/ppdb');
    $secondaryLabel = setting('spmb_card_secondary_label', 'Info Selengkapnya');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section id="spmb" class="mb-6" data-aos="fade-up">
        <div class="rounded-2xl overflow-hidden border border-amber-200"
             style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 60%,#fde68a 100%)">
            <div class="grid lg:grid-cols-2 gap-0">
                <div class="p-8 lg:p-10" data-aos="fade-right" data-aos-delay="100">
                    <div class="fi-badge mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block animate-pulse"></span>
                        Penerimaan Peserta Didik Baru
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-amber-900 leading-snug mb-3">
                        {!! nl2br(e($cardTitle)) !!}
                    </h2>
                    <p class="text-amber-800/80 text-sm leading-relaxed mb-6">
                        {{ $cardDesc }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $ctaUrl }}"
                           class="btn-primary text-sm">
                            {{ $ctaLabel }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('ppdb.index') }}"
                           class="btn-outline border-amber-300 text-amber-900 bg-transparent hover:bg-amber-100 text-sm">
                            {{ $secondaryLabel }}
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex items-center justify-center bg-amber-400/20 p-10"
                     data-aos="fade-left" data-aos-delay="200">
                    <div class="text-center">
                        <div class="text-7xl mb-4">🎓</div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            @foreach([
                                [setting('spmb_deadline', '30 Mei'), 'Batas Daftar'],
                                [setting('spmb_select', '10 Juni'), 'Seleksi'],
                                [setting('spmb_announce', '25 Juni'), 'Pengumuman'],
                            ] as [$d, $l])
                                <div class="bg-white/70 rounded-xl p-3">
                                    <div class="text-sm font-extrabold text-amber-700">{{ $d }}</div>
                                    <div class="text-[10px] text-amber-600 font-medium mt-0.5">{{ $l }}</div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('ppdb.index') }}"
                           class="mt-5 inline-flex items-center gap-1.5 text-xs font-bold text-amber-700 hover:text-amber-900 transition-colors">
                            Lihat semua info PPDB
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
