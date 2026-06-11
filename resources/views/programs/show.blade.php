@extends('layouts.public')

{{-- ── BreadcrumbList Schema ──────────────────────────────── --}}
@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Program', 'item' => route('programs.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $program->title, 'item' => $program->canonical_url],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('head')
<style>
    .program-detail-hero {
        position: relative;
        margin-top: -4rem;
        height: 22rem;
        padding-top: 4rem;
        overflow: hidden;
    }
    @media(min-width: 640px) { .program-detail-hero { height: 28rem; } }
    @media(min-width: 1024px) { .program-detail-hero { height: 32rem; } }
    .program-detail-hero img { width: 100%; height: 100%; object-fit: cover; }
    .program-detail-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,.82) 0%, rgba(0,0,0,.35) 50%, rgba(0,0,0,.1) 100%);
    }

    .program-prose h2 {
        font-size: 1.35rem; font-weight: 800; margin-top: 2.25rem; margin-bottom: .875rem;
        color: #030712; line-height: 1.35; padding-bottom: .5rem;
        border-bottom: 2px solid #fde68a; display: inline-block;
    }
    .program-prose h3 { font-size: 1.1rem; font-weight: 700; margin-top: 1.75rem; margin-bottom: .625rem; color: #1f2937; }
    .program-prose p { margin-bottom: 1.25rem; line-height: 1.85; color: #374151; font-size: .9375rem; }
    .program-prose ul, .program-prose ol { padding-left: 1.5rem; margin-bottom: 1.25rem; color: #374151; }
    .program-prose ul { list-style: disc; }
    .program-prose ol { list-style: decimal; }
    .program-prose li { margin-bottom: .5rem; line-height: 1.75; }
    .program-prose blockquote {
        border-left: 4px solid #d97706; padding: .75rem 1.25rem; margin: 1.75rem 0;
        background: #fffbeb; border-radius: 0 .5rem .5rem 0; color: #78350f; font-style: italic;
    }
    .program-prose a { color: #d97706; text-decoration: underline; text-underline-offset: 3px; font-weight: 500; }
    .program-prose strong { color: #030712; font-weight: 700; }
    .program-prose img { border-radius: .75rem; margin: 1.5rem 0; max-width: 100%; }

    .sidebar-sticky { position: sticky; top: 5rem; }

    .related-card {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 1rem; overflow: hidden;
        transition: transform .25s, box-shadow .25s, border-color .25s;
    }
    .related-card:hover { transform: translateY(-4px); box-shadow: 0 16px 32px rgba(0,0,0,.1); border-color: #fcd34d; }
</style>
@endpush

@section('content')

    {{-- ── Hero ─────────────────────────────────────────────── --}}
    @if($program->image)
        <div class="program-detail-hero">
            <img src="{{ $program->thumbnail_url }}" alt="{{ $program->title }}" loading="eager">
            <div class="program-detail-hero-overlay"></div>
            <div class="absolute bottom-0 inset-x-0 p-6 sm:p-10">
                <div class="max-w-4xl mx-auto">
                    <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs text-white/60 mb-3">
                        <a href="/" class="hover:text-white transition-colors">Beranda</a>
                        <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <a href="{{ route('programs.index') }}" class="hover:text-white transition-colors">Program</a>
                    </nav>
                    @if($program->category)
                        <span class="inline-block text-[11px] font-bold px-3 py-1.5 rounded-full bg-amber-500 text-white mb-3 uppercase tracking-wide">
                            {{ $program->category }}
                        </span>
                    @endif
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-snug tracking-tight max-w-3xl">
                        {{ $program->icon }} {{ $program->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
        <section class="program-hero -mt-17 pt-30 pb-14 sm:pt-36 sm:pb-20"
                 style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0c1a14 100%)">
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav aria-label="Breadcrumb" class="flex items-center gap-1.5 text-xs mb-5 text-white/50">
                    <a href="/" class="hover:text-white transition-colors">Beranda</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('programs.index') }}" class="hover:text-white transition-colors">Program</a>
                </nav>
                @if($program->category)
                    <span class="inline-block text-[11px] font-bold px-3 py-1.5 rounded-full bg-amber-500 text-white mb-3 uppercase tracking-wide">
                        {{ $program->category }}
                    </span>
                @endif
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                    {{ $program->icon }} {{ $program->title }}
                </h1>
            </div>
        </section>
    @endif

    {{-- ── Body ─────────────────────────────────────────────── --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" data-aos="fade-up" data-aos-duration="500">
        <div class="grid lg:grid-cols-4 gap-10">

            <div class="lg:col-span-3">
                @if($program->excerpt)
                    <p class="text-base font-medium leading-relaxed mb-8 p-5 rounded-xl border-l-4 border-amber-400 bg-amber-50 text-amber-900">
                        {{ $program->excerpt }}
                    </p>
                @endif

                <div class="program-prose">
                    {!! $program->description !!}
                </div>

                {{-- ── Gallery ──────────────────────────────────── --}}
                @if(! empty($program->gallery_urls))
                    <div class="mt-10 pt-8 border-t border-gray-100"
                         x-data="{ open: false, active: '', images: @js($program->gallery_urls) }">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Galeri Program</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($program->gallery_urls as $url)
                                <button type="button"
                                        @click="active = '{{ $url }}'; open = true"
                                        class="group relative aspect-square overflow-hidden rounded-xl border border-gray-100 focus:outline-none focus:ring-2 focus:ring-amber-400">
                                    <img src="{{ $url }}"
                                         alt="{{ $program->title }} — galeri {{ $loop->iteration }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                        <svg class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        {{-- Lightbox --}}
                        <div x-show="open" x-cloak
                             x-transition.opacity
                             @keydown.escape.window="open = false"
                             @click="open = false"
                             class="fixed inset-0 z-100 flex items-center justify-center bg-black/85 p-4"
                             style="display:none">
                            <button type="button" @click="open = false"
                                    class="absolute top-5 right-5 text-white/80 hover:text-white">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <img :src="active" @click.stop alt="{{ $program->title }}"
                                 class="max-w-full max-h-[85vh] rounded-lg object-contain shadow-2xl">
                        </div>
                    </div>
                @endif

                {{-- Share buttons --}}
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Bagikan program ini</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="https://wa.me/?text={{ urlencode($program->title.' - '.$program->canonical_url) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background:#25d366">
                            WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($program->canonical_url) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background:#1877f2">
                            Facebook
                        </a>
                        <button onclick="navigator.clipboard.writeText('{{ $program->canonical_url }}').then(()=>{ this.textContent='✓ Disalin!'; setTimeout(()=>{ this.textContent='Salin Link'; },2000); })"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-opacity hover:opacity-90" style="background:#d97706">
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Sidebar ────────────────────────────────────── --}}
            <aside class="lg:col-span-1">
                <div class="sidebar-sticky space-y-4">
                    @if($program->category)
                        <div class="fi-card p-5">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-amber-600 mb-3">Kategori</div>
                            <a href="{{ route('programs.index', ['category' => $program->category]) }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:text-amber-800 hover:underline transition-colors">
                                <span class="w-6 h-6 rounded-lg bg-amber-50 border border-amber-200 flex items-center justify-center text-xs">📂</span>
                                {{ $program->category }}
                            </a>
                        </div>
                    @endif

                    <a href="{{ route('ppdb.index') }}" class="btn-primary w-full justify-center text-xs">
                        Daftar Sekarang
                    </a>

                    <a href="{{ route('programs.index') }}" class="btn-outline w-full justify-center text-xs group">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Semua Program
                    </a>
                </div>
            </aside>
        </div>
    </div>

    {{-- ── Related Programs ─────────────────────────────────── --}}
    @if($related->isNotEmpty())
        <section class="border-t py-14" style="border-color:#f3f4f6;background:#f9fafb">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-amber-600 mb-2">
                    <span class="w-4 h-px bg-amber-500 inline-block"></span>
                    Lainnya
                </span>
                <h2 class="text-xl font-extrabold mb-8 text-gray-900">Program Terkait</h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $rel)
                        <a href="{{ route('programs.show', $rel->slug) }}"
                           class="related-card group flex flex-col p-6"
                           data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                            <div class="flex items-center gap-3 mb-3">
                                @if($rel->icon)
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 bg-amber-50">
                                        {{ $rel->icon }}
                                    </div>
                                @endif
                                <h3 class="font-bold text-sm leading-snug line-clamp-2 text-gray-900 group-hover:text-amber-700 transition-colors">
                                    {{ $rel->title }}
                                </h3>
                            </div>
                            @if($rel->excerpt)
                                <p class="text-xs leading-relaxed line-clamp-2 text-gray-400">{{ $rel->excerpt }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
