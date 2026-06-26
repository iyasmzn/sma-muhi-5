@extends('layouts.public')

{{-- ── Structured Data (JSON-LD) ─────────────────────────── --}}
@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'CollectionPage',
    'name'        => $seo['title'],
    'description' => $seo['description'],
    'url'         => $seo['canonical'],
    'publisher'   => [
        '@type' => 'EducationalOrganization',
        'name'  => setting('site_name', config('app.name')),
        'url'   => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('head')
<style>
    .gallery-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0c1a14 100%);
        position: relative;
        overflow: hidden;
    }
    .gallery-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 70% at 10% 50%, rgba(217,119,6,.22) 0%, transparent 55%),
            radial-gradient(ellipse 50% 50% at 90% 10%, rgba(251,191,36,.1) 0%, transparent 50%);
    }
    .gallery-hero-dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    .gallery-pill {
        font-size: .8125rem;
        font-weight: 600;
        padding: .5rem 1.125rem;
        border-radius: 9999px;
        border: 1.5px solid var(--border);
        color: var(--muted);
        background: var(--card);
        transition: all .2s;
        white-space: nowrap;
    }
    .gallery-pill:hover {
        border-color: var(--color-amber-300);
        color: var(--color-amber-800);
        background: var(--color-amber-50);
    }
    .gallery-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .gallery-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 1.25rem;
        overflow: hidden;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }
    .gallery-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0,0,0,.12);
        border-color: var(--color-amber-200);
    }
    .gallery-card:hover .gallery-card-img { transform: scale(1.06); }
    .gallery-card-img { transition: transform .6s ease; }
</style>
@endpush

@php
    $lightboxItems = $items->getCollection()->map(fn ($m) => [
        'type' => $m->is_embed ? 'video' : 'image',
        'name' => $m->name,
        'src'  => $m->is_embed ? null : $m->url,
        'html' => $m->is_embed ? (string) $m->embed_html : null,
        'provider' => $m->is_embed ? $m->embed_provider : null,
        'embedSrc' => $m->is_embed ? \App\Services\EmbedVideo::embedSrc($m->embed_provider, $m->embed_url ?? '') : null,
        'vertical' => in_array($m->embed_provider, ['tiktok', 'instagram'], true),
        'ratio' => $m->is_embed ? round(\App\Services\EmbedVideo::aspectRatio($m->embed_provider), 4) : null,
    ])->values();
@endphp

@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────── --}}
    <section class="gallery-hero -mt-17 pt-30 pb-14 sm:pt-36 sm:pb-20">
        <div class="gallery-hero-dots"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-5 text-white/50">
                <a href="/" class="hover:text-white transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-amber-400 font-medium">Galeri</span>
            </nav>

            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-amber-400 mb-3" data-aos="fade-up">
                <span class="w-4 h-px bg-amber-400 inline-block"></span>
                Foto & Video
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight mb-3" data-aos="fade-up" data-aos-delay="60">
                Galeri Sekolah
            </h1>
            <p class="text-white/60 text-sm sm:text-base max-w-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Dokumentasi kegiatan, fasilitas, dan prestasi di {{ setting('site_name', config('app.name')) }}.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{
            open: false,
            active: null,
            items: @js($lightboxItems),
            show(i) { this.active = i; this.open = true; document.body.style.overflow = 'hidden'; },
            close() { this.open = false; this.active = null; document.body.style.overflow = ''; },
            get current() { return this.active !== null ? this.items[this.active] : null; },
            igMeasure(event, iframe) {
                if (! iframe || event.source !== iframe.contentWindow) { return; }
                let data = event.data;
                try { data = typeof data === 'string' ? JSON.parse(data) : data; } catch (e) { return; }
                if (data && data.type === 'MEASURE' && data.details && data.details.height) {
                    iframe.style.height = Math.min(data.details.height, window.innerHeight * 0.85) + 'px';
                }
            },
         }"
         @keydown.escape.window="close()">

        {{-- ── Type Filter ─────────────────────────────────── --}}
        <div class="flex flex-wrap gap-2 mb-8" data-aos="fade-up">
            <a href="{{ route('gallery.index') }}" class="gallery-pill {{ ! $type ? 'active' : '' }}">Semua</a>
            <a href="{{ route('gallery.index', ['type' => 'foto']) }}" class="gallery-pill {{ $type === 'foto' ? 'active' : '' }}">Foto</a>
            <a href="{{ route('gallery.index', ['type' => 'video']) }}" class="gallery-pill {{ $type === 'video' ? 'active' : '' }}">Video</a>
        </div>

        {{-- ── Gallery Grid ────────────────────────────────── --}}
        @if($items->isEmpty())
            <div class="text-center py-24" data-aos="fade-up">
                <div class="w-20 h-20 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5 text-4xl">🖼️</div>
                <p class="text-base font-semibold text-gray-700 mb-1">Belum ada media</p>
                <p class="text-sm text-gray-400 mb-5">Belum ada foto atau video pada kategori ini.</p>
                <a href="{{ route('gallery.index') }}" class="btn-outline inline-flex">Lihat Semua</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                @foreach($items as $item)
                    <a href="{{ $item->is_embed ? $item->embed_url : $item->url }}"
                       @if($item->is_embed) target="_blank" rel="noopener" @endif
                       @click.prevent="show({{ $loop->index }})"
                       class="gallery-card group relative block"
                       data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 70 }}">

                        <div class="aspect-4/3 overflow-hidden bg-gray-100">
                            <img src="{{ $item->thumbnail_url }}"
                                 alt="{{ $item->alt ?? $item->name }}"
                                 loading="lazy"
                                 class="gallery-card-img w-full h-full object-cover">
                        </div>

                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        {{-- Type badge --}}
                        <span class="absolute top-3 left-3 inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-amber-700">
                            @if($item->is_embed)
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            @endif
                            {{ $item->getTypeLabel() }}
                        </span>

                        {{-- Play overlay for videos --}}
                        @if($item->is_embed)
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <span class="w-12 h-12 rounded-full bg-white/85 backdrop-blur-sm flex items-center justify-center shadow-lg transition-transform duration-300 group-hover:scale-110">
                                    <svg class="w-5 h-5 text-amber-700 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </div>
                        @endif

                        {{-- Caption --}}
                        <div class="absolute inset-x-0 bottom-0 p-3 translate-y-1 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <h2 class="text-white text-xs sm:text-sm font-semibold leading-snug line-clamp-2">{{ $item->name }}</h2>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($items->hasPages())
                <div class="mt-10">
                    {{ $items->links() }}
                </div>
            @endif
        @endif

        {{-- ── Lightbox ────────────────────────────────────── --}}
        <div x-cloak x-show="open"
             x-transition.opacity
             class="fixed inset-0 z-80 flex items-center justify-center p-4 sm:p-8"
             style="background:rgba(0,0,0,.85)"
             @click.self="close()">

            {{-- Close --}}
            <button @click="close()"
                    class="absolute top-4 right-4 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors"
                    aria-label="Tutup">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="w-full max-w-5xl" @click.stop x-show="current">
                <template x-if="current && current.type === 'image'">
                    <img :src="current.src" :alt="current.name"
                         class="max-h-[80vh] w-auto mx-auto rounded-xl shadow-2xl object-contain">
                </template>
                <template x-if="current && current.type === 'video' && current.provider === 'instagram'">
                    <iframe :src="current.embedSrc" scrolling="no" loading="lazy"
                            class="block mx-auto rounded-xl shadow-2xl bg-white border-0"
                            style="width: min(540px, 92vw); height: 70vh;"
                            @load="$el.style.height = '70vh'"
                            @message.window="igMeasure($event, $el)"></iframe>
                </template>
                <template x-if="current && current.type === 'video' && current.provider !== 'instagram'">
                    <div class="mx-auto rounded-xl overflow-hidden shadow-2xl"
                         :style="current.vertical ? `width: min(calc(80vh * ${current.ratio}), 90vw)` : ''"
                         x-html="current.html"></div>
                </template>
                <p class="text-center text-white/80 text-sm mt-4 font-medium" x-text="current ? current.name : ''"></p>
            </div>
        </div>

    </div>

@endsection
