@php
    /**
     * Shared error hero.
     * Expects $code (int). Falls back to the HttpException status when available.
     * Content (label/title/message) is editable from the admin panel via settings,
     * falling back to the sensible defaults below.
     */
    $code = $code ?? (isset($exception) && method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);

    $errorDefaults = [
        403 => ['label' => 'Akses Ditolak',            'title' => 'Kamu tidak punya akses',           'message' => 'Halaman ini bersifat terbatas. Jika kamu merasa ini sebuah kesalahan, silakan hubungi administrator.'],
        404 => ['label' => 'Halaman Tidak Ditemukan',   'title' => 'Sepertinya kamu tersesat',          'message' => 'Halaman yang kamu cari mungkin sudah dipindahkan, dihapus, atau alamatnya salah ketik.'],
        419 => ['label' => 'Sesi Berakhir',             'title' => 'Sesi kamu telah berakhir',          'message' => 'Demi keamanan, sesi kamu telah kedaluwarsa. Silakan muat ulang halaman lalu coba lagi.'],
        429 => ['label' => 'Terlalu Banyak Permintaan', 'title' => 'Pelan-pelan dulu',                  'message' => 'Kamu mengirim terlalu banyak permintaan dalam waktu singkat. Mohon tunggu beberapa saat lalu coba lagi.'],
        500 => ['label' => 'Kesalahan Server',          'title' => 'Ada yang tidak beres',              'message' => 'Terjadi kesalahan di server kami. Tim kami sudah diberi tahu dan sedang menanganinya.'],
        503 => ['label' => 'Sedang Pemeliharaan',       'title' => 'Website sedang dalam pemeliharaan', 'message' => 'Kami sedang melakukan perbaikan agar layanan menjadi lebih baik. Silakan kembali beberapa saat lagi.'],
    ];

    $d = $errorDefaults[$code] ?? ['label' => 'Terjadi Kesalahan', 'title' => 'Ups, ada yang salah', 'message' => 'Maaf, terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.'];

    $errLabel   = setting("error_{$code}_label")   ?: $d['label'];
    $errTitle   = setting("error_{$code}_title")   ?: $d['title'];
    $errMessage = setting("error_{$code}_message") ?: $d['message'];

    $isServerError = $code >= 500;

    $errorLinks = collect(json_decode(setting('nav_items', ''), true) ?: [
        ['label' => 'Beranda', 'url' => '/',        'is_active' => true],
        ['label' => 'Guru',    'url' => '/guru',    'is_active' => true],
        ['label' => 'Blog',    'url' => '/blog',    'is_active' => true],
        ['label' => 'Unduhan', 'url' => '/unduhan', 'is_active' => true],
    ])->where('is_active', true)->values();
@endphp

@push('head')
<style>
    .error-code {
        font-size: clamp(6rem, 22vw, 12rem);
        background: linear-gradient(135deg, #fbbf24 0%, #d97706 55%, #92400e 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -.03em;
        filter: drop-shadow(0 12px 40px rgba(217,119,6,.25));
    }
    .error-glow {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 50% 30%, rgba(217,119,6,.20) 0%, transparent 60%),
            radial-gradient(ellipse 40% 40% at 85% 80%, rgba(251,191,36,.10) 0%, transparent 55%);
        pointer-events: none;
    }
    .error-dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 30px 30px;
        pointer-events: none;
    }
    /* Footer is irrelevant on a full-screen error — tighten the gap */
    .error-hero + footer { margin-top: 0 !important; }
</style>
@endpush

{{-- ── Error Hero — dark, centered, pulled under the transparent header ── --}}
<section class="error-hero -mt-17 relative overflow-hidden flex items-center"
         style="min-height:100vh; background:linear-gradient(135deg,#0f172a 0%,#1e293b 45%,#0c1a14 100%)">

    {{-- Decorative glow + dots --}}
    <div class="error-glow"></div>
    <div class="error-dots"></div>

    <div class="relative z-10 w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-28 text-center">

        {{-- Big status code --}}
        <h1 class="error-code font-extrabold leading-none mb-4" data-aos="fade-up">{{ $code }}</h1>

        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-amber-400 mb-4"
              data-aos="fade-up" data-aos-delay="60">
            <span class="w-4 h-px bg-amber-400 inline-block"></span>
            {{ $errLabel }}
        </span>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-3"
            data-aos="fade-up" data-aos-delay="100">
            {{ $errTitle }}
        </h2>

        <p class="text-white/55 text-sm sm:text-base max-w-md mx-auto leading-relaxed mb-9"
           data-aos="fade-up" data-aos-delay="140">
            {{ $errMessage }}
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-12"
             data-aos="fade-up" data-aos-delay="180">
            <a href="/" class="btn-primary justify-center w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
            @if($isServerError)
                <button onclick="window.location.reload()"
                        class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-7 py-3 rounded-[.875rem] text-sm font-semibold border border-white/25 text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Muat Ulang
                </button>
            @else
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-7 py-3 rounded-[.875rem] text-sm font-semibold border border-white/25 text-white/80 hover:bg-white/10 hover:text-white transition-all duration-200">
                    Lihat Blog
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            @endif
        </div>

        {{-- Quick links --}}
        <div class="pt-8 border-t border-white/10" data-aos="fade-up" data-aos-delay="220">
            <p class="text-[11px] font-bold uppercase tracking-widest text-white/30 mb-4">Atau kunjungi</p>
            <nav class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2">
                @foreach($errorLinks as $link)
                    @php $linkUrl = str_starts_with($link['url'], '#') ? '/' . $link['url'] : $link['url']; @endphp
                    <a href="{{ $linkUrl }}"
                       class="text-sm font-medium text-white/55 hover:text-amber-400 transition-colors">{{ $link['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </div>
</section>
