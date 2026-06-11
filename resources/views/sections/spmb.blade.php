@php
    $spmbYear       = setting('spmb_year', '2026/2027');
    $cardTitle      = setting('spmb_card_title', 'SPMB Tahun Ajaran {year} Dibuka!');
    $cardTitle      = str_replace('{year}', $spmbYear, $cardTitle);
    $cardDesc       = setting('spmb_card_description', 'Pendaftaran peserta didik baru resmi dibuka. Tersedia jalur Prestasi, Zonasi, dan Afirmasi. Segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu.');
    $ctaLabel       = setting('spmb_card_cta_label', 'Daftar Sekarang');
    $ctaUrl         = setting('spmb_card_cta_url', '/ppdb');
    $secondaryLabel = setting('spmb_card_secondary_label', 'Info Selengkapnya');
@endphp

<section id="spmb" class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl"
             style="box-shadow:0 30px 80px -20px color-mix(in oklab, var(--primary) 45%, transparent);
                    background:
                        radial-gradient(120% 120% at 100% 0%, color-mix(in oklab, var(--color-amber-500) 55%, transparent) 0%, transparent 55%),
                        radial-gradient(100% 120% at 0% 100%, color-mix(in oklab, var(--color-amber-800) 60%, transparent) 0%, transparent 50%),
                        linear-gradient(135deg, var(--color-amber-800) 0%, color-mix(in oklab, var(--primary) 70%, black) 55%, var(--color-amber-900) 100%);"
             data-aos="fade-up">

            {{-- Aurora glow --}}
            <div class="pointer-events-none absolute -top-32 right-1/4 w-md h-112 rounded-full blur-3xl opacity-40"
                 style="background:radial-gradient(circle at center, var(--color-amber-400), transparent 70%)"></div>
            {{-- Dotted grid overlay --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.15]"
                 style="background-image:radial-gradient(rgba(255,255,255,.7) 1px, transparent 1px); background-size:22px 22px;"></div>
            {{-- Top highlight edge --}}
            <div class="pointer-events-none absolute inset-x-0 top-0 h-px"
                 style="background:linear-gradient(90deg, transparent, rgba(255,255,255,.45) 50%, transparent)"></div>

            <div class="relative grid lg:grid-cols-2">

                {{-- Left: copy --}}
                <div class="p-10 lg:p-14" data-aos="fade-right" data-aos-delay="80">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-5 text-white"
                         style="border:1px solid rgba(255,255,255,.25); background:rgba(255,255,255,.10); backdrop-filter:blur(6px);">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse inline-block"></span>
                        Penerimaan Peserta Didik Baru
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight mb-4 text-white">
                        {!! nl2br(e($cardTitle)) !!}
                    </h2>
                    <p class="text-base leading-relaxed mb-8 text-white/75">
                        {{ $cardDesc }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $ctaUrl }}"
                           class="inline-flex items-center gap-2 px-7 py-3 rounded-2xl font-semibold text-base shadow-lg transition-all hover:-translate-y-0.5"
                           style="background:#fff; color:var(--color-amber-800); box-shadow:0 10px 30px -8px rgba(0,0,0,.4);">
                            {{ $ctaLabel }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('ppdb.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-semibold text-base text-white transition-all hover:bg-white/10"
                           style="border:1.5px solid rgba(255,255,255,.35);">
                            {{ $secondaryLabel }}
                        </a>
                    </div>
                </div>

                {{-- Right: dates panel --}}
                <div class="hidden lg:flex items-center justify-center px-10 py-14"
                     style="border-left:1px solid rgba(255,255,255,.12);"
                     data-aos="fade-left" data-aos-delay="160">
                    <div class="text-center w-full">
                        <div class="text-8xl mb-6 drop-shadow-lg">🎓</div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            @foreach([
                                [setting('spmb_deadline', '30 Mei'), 'Batas Daftar'],
                                [setting('spmb_select', '10 Juni'), 'Seleksi'],
                                [setting('spmb_announce', '25 Juni'), 'Pengumuman'],
                            ] as [$d, $l])
                                <div class="rounded-2xl p-4 transition-transform hover:-translate-y-1"
                                     style="background:rgba(255,255,255,.10);
                                            backdrop-filter:blur(10px); -webkit-backdrop-filter:blur(10px);
                                            border:1px solid rgba(255,255,255,.18);
                                            box-shadow:0 8px 24px -12px rgba(0,0,0,.5);">
                                    <div class="text-sm font-extrabold text-white">{{ $d }}</div>
                                    <div class="text-[11px] font-semibold mt-1 text-white/65">{{ $l }}</div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('ppdb.index') }}"
                           class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold text-white transition-opacity hover:opacity-70">
                            Lihat semua info PPDB
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
