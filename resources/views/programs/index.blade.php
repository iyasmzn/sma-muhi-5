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
    .program-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0c1a14 100%);
        position: relative;
        overflow: hidden;
    }
    .program-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 70% at 10% 50%, rgba(217,119,6,.22) 0%, transparent 55%),
            radial-gradient(ellipse 50% 50% at 90% 10%, rgba(251,191,36,.1) 0%, transparent 50%);
    }
    .program-hero-dots {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 28px 28px;
    }

    .cat-pill {
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
    .cat-pill:hover {
        border-color: var(--color-amber-300);
        color: var(--color-amber-800);
        background: var(--color-amber-50);
    }
    .cat-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .program-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 1.5rem;
        overflow: hidden;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }
    .program-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0,0,0,.12);
        border-color: var(--color-amber-200);
    }
    .program-card:hover .program-card-img {
        transform: scale(1.06);
    }
    .program-card-img { transition: transform .6s ease; }
</style>
@endpush

@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────── --}}
    <section class="program-hero -mt-17 pt-30 pb-14 sm:pt-36 sm:pb-20">
        <div class="program-hero-dots"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-5 text-white/50">
                <a href="/" class="hover:text-white transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-amber-400 font-medium">Program</span>
            </nav>

            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-amber-400 mb-3" data-aos="fade-up">
                <span class="w-4 h-px bg-amber-400 inline-block"></span>
                Program Sekolah
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight mb-3" data-aos="fade-up" data-aos-delay="60">
                {{ $category ? "Kategori: {$category}" : 'Program Sekolah' }}
            </h1>
            <p class="text-white/60 text-sm sm:text-base max-w-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Program unggulan, ekstrakurikuler, dan kegiatan pembelajaran di {{ setting('site_name', config('app.name')) }}.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Category Filter ─────────────────────────────── --}}
        @if($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8" data-aos="fade-up">
                <a href="{{ route('programs.index') }}" class="cat-pill {{ !$category ? 'active' : '' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('programs.index', ['category' => $cat]) }}"
                       class="cat-pill {{ $category === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ── Program Grid ────────────────────────────────── --}}
        @if($programs->isEmpty())
            <div class="text-center py-24" data-aos="fade-up">
                <div class="w-20 h-20 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5 text-4xl">🎓</div>
                <p class="text-base font-semibold text-gray-700 mb-1">Belum ada program</p>
                <p class="text-sm text-gray-400 mb-5">Belum ada program untuk kategori ini.</p>
                <a href="{{ route('programs.index') }}" class="btn-outline inline-flex">Lihat Semua Program</a>
            </div>
        @else
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($programs as $program)
                    <a href="{{ route('programs.show', $program->slug) }}"
                       class="program-card group flex flex-col"
                       data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}">

                        @if($program->image)
                            <div class="relative h-48 block overflow-hidden">
                                <img src="{{ $program->thumbnail_url }}"
                                     alt="{{ $program->title }}"
                                     class="program-card-img w-full h-full object-cover"
                                     loading="lazy">
                                <div class="absolute inset-0 bg-linear-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @if($program->category)
                                    <div class="absolute top-3 left-3">
                                        <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-white/90 backdrop-blur-sm text-amber-700">
                                            {{ $program->category }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                @if($program->icon)
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 bg-amber-50">
                                        {{ $program->icon }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h2 class="font-bold text-sm leading-snug line-clamp-2 text-gray-900 group-hover:text-amber-700 transition-colors">
                                        {{ $program->title }}
                                    </h2>
                                    @if($program->category && ! $program->image)
                                        <span class="text-[11px] font-semibold text-amber-700">{{ $program->category }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($program->excerpt)
                                <p class="text-xs leading-relaxed line-clamp-3 text-gray-400 mb-4 flex-1">
                                    {{ $program->excerpt }}
                                </p>
                            @endif

                            <span class="text-xs font-bold text-amber-600 group-hover:text-amber-700 flex items-center gap-1 mt-auto">
                                Selengkapnya
                                <svg class="w-3 h-3 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </div>

@endsection
