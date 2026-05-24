@extends('layouts.public')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'  => 'https://schema.org',
    '@type'     => 'Person',
    'name'      => $teacher->name,
    'jobTitle'  => $teacher->position,
    'image'     => $teacher->photo_url,
    'url'       => $seo['canonical'],
    'telephone' => $teacher->phone ?: null,
    'email'     => $teacher->email ?: null,
    'worksFor'  => [
        '@type' => 'EducationalOrganization',
        'name'  => setting('site_name', config('app.name')),
        'url'   => url('/'),
    ],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('breadcrumb')
    <a href="/" class="hover:text-amber-600 transition-colors">Beranda</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('teachers.index') }}" class="hover:text-amber-600 transition-colors">Tenaga Pendidik</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-amber-600 font-medium truncate max-w-48">{{ $teacher->name }}</span>
@endpush

@section('content')

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Breadcrumb mobile ──────────────────────────── --}}
        <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-6" style="color:#6b7280">
            <a href="/" class="hover:text-amber-600">Beranda</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('teachers.index') }}" class="hover:text-amber-600">Tenaga Pendidik</a>
            <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-amber-600 truncate max-w-32">{{ $teacher->name }}</span>
        </nav>

        {{-- ── Main Card ────────────────────────────────────── --}}
        <div class="fi-card overflow-hidden" data-aos="fade-up" itemscope itemtype="https://schema.org/Person">

            <div class="grid md:grid-cols-3 gap-0">

                {{-- ── Left: Photo ──────────────────────────── --}}
                <div class="md:col-span-1 relative overflow-hidden bg-amber-50" style="min-height:340px">
                    <img src="{{ $teacher->photo_url }}"
                         alt="{{ $teacher->name }}"
                         loading="eager"
                         class="w-full h-full object-cover"
                         style="max-height:500px"
                         itemprop="image">

                    {{-- Amber gradient overlay at bottom --}}
                    <div class="absolute bottom-0 inset-x-0 h-24 bg-linear-to-t from-black/60 to-transparent md:hidden"></div>
                </div>

                {{-- ── Right: Info ───────────────────────────── --}}
                <div class="md:col-span-2 p-8 flex flex-col justify-center">

                    {{-- Position badge --}}
                    <span class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full text-xs font-bold mb-4 border"
                          style="background:#fffbeb;color:#92400e;border-color:#fde68a">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422A12.083 12.083 0 0121 16.75c0 2.485-4.03 4.5-9 4.5s-9-2.015-9-4.5a12.083 12.083 0 012.84-5.172L12 14z"/>
                        </svg>
                        {{ $teacher->position }}
                    </span>

                    <h1 class="text-2xl sm:text-3xl font-extrabold leading-snug mb-6"
                        style="color:#030712" itemprop="name">
                        {{ $teacher->name }}
                    </h1>

                    {{-- Detail grid --}}
                    <dl class="space-y-4">
                        @if($teacher->nip)
                            <div class="flex gap-3">
                                <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide shrink-0 w-36"
                                    style="color:#d97706">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                    NIP
                                </dt>
                                <dd class="text-sm font-mono" style="color:#374151" itemprop="identifier">
                                    {{ $teacher->nip }}
                                </dd>
                            </div>
                        @endif

                        @if($teacher->subject)
                            <div class="flex gap-3">
                                <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide shrink-0 w-36"
                                    style="color:#d97706">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    Mata Pelajaran
                                </dt>
                                <dd class="text-sm" style="color:#374151" itemprop="jobTitle">
                                    {{ $teacher->subject }}
                                </dd>
                            </div>
                        @endif

                        @if($teacher->education)
                            <div class="flex gap-3">
                                <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide shrink-0 w-36"
                                    style="color:#d97706">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                    Pendidikan
                                </dt>
                                <dd class="text-sm" style="color:#374151">
                                    {{ $teacher->education }}
                                </dd>
                            </div>
                        @endif

                        <div class="flex gap-3">
                            <dt class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide shrink-0 w-36"
                                style="color:#d97706">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Status
                            </dt>
                            <dd class="text-sm">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="background:#d1fae5;color:#065f46">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                    Aktif Mengajar
                                </span>
                            </dd>
                        </div>
                    </dl>

                    {{-- ── Kontak ──────────────────────────────── --}}
                    @if($teacher->phone || $teacher->email || $teacher->whatsapp)
                        <div class="mt-6 pt-5 border-t" style="border-color:#e5e7eb">
                            <h2 class="text-xs font-bold uppercase tracking-wide mb-3" style="color:#d97706">Kontak</h2>
                            <div class="flex flex-wrap gap-2">
                                @if($teacher->phone)
                                    <a href="tel:{{ $teacher->phone }}"
                                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700"
                                       style="border-color:#e5e7eb;color:#374151">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        {{ $teacher->phone }}
                                    </a>
                                @endif

                                @if($teacher->email)
                                    <a href="mailto:{{ $teacher->email }}"
                                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700"
                                       style="border-color:#e5e7eb;color:#374151"
                                       itemprop="email">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $teacher->email }}
                                    </a>
                                @endif

                                @if($teacher->whatsapp)
                                    <a href="https://wa.me/{{ $teacher->whatsapp }}"
                                       target="_blank" rel="noopener"
                                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all hover:bg-green-50 hover:border-green-400 hover:text-green-700"
                                       style="border-color:#e5e7eb;color:#374151">
                                        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Back button --}}
                    <div class="mt-6 pt-5 border-t" style="border-color:#e5e7eb">
                        <a href="{{ route('teachers.index') }}" class="btn-outline text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Daftar Guru
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
