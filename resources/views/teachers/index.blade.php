@extends('layouts.public')

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

@push('breadcrumb')
    <a href="/" class="hover:text-amber-600 transition-colors">Beranda</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-amber-600 font-medium">Tenaga Pendidik</span>
@endpush

@section('content')

    {{-- ── Page Header ──────────────────────────────────────── --}}
    <section class="border-b py-10" style="background:linear-gradient(135deg,#fffbeb,#fef3c7 60%,#fff);border-color:#fde68a">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb mobile --}}
            <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-4" style="color:#6b7280">
                <a href="/" class="hover:text-amber-600">Beranda</a>
                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-amber-600 font-medium">Tenaga Pendidik</span>
            </nav>

            <div class="fi-label mb-2" data-aos="fade-up">Profil Sekolah</div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-amber-900 mb-2" data-aos="fade-up" data-aos-delay="60">
                Tenaga Pendidik
            </h1>
            <p class="text-amber-800/70 text-sm max-w-xl" data-aos="fade-up" data-aos-delay="100">
                Guru-guru profesional dan berdedikasi yang membimbing siswa menuju prestasi terbaik di {{ setting('site_name', config('app.name')) }}.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Search & Filter Bar ──────────────────────────── --}}
        <form method="GET" action="{{ route('teachers.index') }}"
              class="fi-card p-4 mb-8 flex flex-col sm:flex-row gap-3" data-aos="fade-up">

            {{-- Search --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama guru..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm border rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition"
                       style="border-color:#e5e7eb;color:#030712;background:#fff">
            </div>

            {{-- Position filter --}}
            @if($positions->isNotEmpty())
                <select name="position"
                        class="sm:w-52 px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition"
                        style="border-color:#e5e7eb;color:#6b7280;background:#fff">
                    <option value="">Semua Jabatan</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}" @selected($position === $pos)>{{ $pos }}</option>
                    @endforeach
                </select>
            @endif

            {{-- Sort --}}
            <select name="sort"
                    class="sm:w-44 px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition"
                    style="border-color:#e5e7eb;color:#6b7280;background:#fff">
                <option value="default"   @selected($sort === 'default')>Urutan Default</option>
                <option value="name_asc"  @selected($sort === 'name_asc')>Nama A–Z</option>
                <option value="name_desc" @selected($sort === 'name_desc')>Nama Z–A</option>
                <option value="position"  @selected($sort === 'position')>Jabatan</option>
            </select>

            <button type="submit"
                    class="btn-primary shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Cari
            </button>

            {{-- Reset link --}}
            @if($search || $position || $sort !== 'default')
                <a href="{{ route('teachers.index') }}"
                   class="btn-outline shrink-0 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset
                </a>
            @endif
        </form>

        {{-- ── Active filters summary ───────────────────────── --}}
        @if($search || $position)
            <p class="text-xs mb-6" style="color:#6b7280">
                Menampilkan <strong class="text-amber-700">{{ $teachers->total() }}</strong> guru
                @if($search) yang mengandung "<strong class="text-amber-700">{{ $search }}</strong>" @endif
                @if($position) dengan jabatan "<strong class="text-amber-700">{{ $position }}</strong>" @endif
            </p>
        @endif

        {{-- ── Teacher Grid ─────────────────────────────────── --}}
        @if($teachers->isEmpty())
            <div class="text-center py-20 fi-card" data-aos="fade-up">
                <div class="text-5xl mb-4">👨‍🏫</div>
                <p class="text-sm font-medium" style="color:#6b7280">Tidak ada guru yang sesuai pencarian.</p>
                <a href="{{ route('teachers.index') }}" class="mt-4 btn-outline inline-flex">Lihat Semua Guru</a>
            </div>
        @else
            <div class="grid gap-5 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($teachers as $teacher)
                    <div class="fi-card fi-card-hover group flex flex-col overflow-hidden"
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 60 }}"
                         itemscope itemtype="https://schema.org/Person">

                        {{-- Photo (link ke detail) --}}
                        <a href="{{ route('teachers.show', $teacher) }}" class="block relative overflow-hidden bg-amber-50 shrink-0" style="aspect-ratio:3/4">
                            <img src="{{ $teacher->photo_url }}"
                                 alt="{{ $teacher->name }}"
                                 loading="{{ $loop->index < 8 ? 'eager' : 'lazy' }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 itemprop="image">
                            {{-- Position badge overlay --}}
                            <div class="absolute bottom-0 inset-x-0 bg-linear-to-t from-black/70 via-black/30 to-transparent px-3 py-2.5">
                                <span class="text-[10px] font-bold text-amber-300 uppercase tracking-wide line-clamp-1">
                                    {{ $teacher->position }}
                                </span>
                            </div>
                        </a>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col flex-1">
                            <a href="{{ route('teachers.show', $teacher) }}"
                               class="font-bold text-sm leading-snug mb-1 hover:text-amber-700 transition-colors line-clamp-2 block"
                               style="color:#030712" itemprop="name">
                                {{ $teacher->name }}
                            </a>
                            @if($teacher->subject)
                                <p class="text-[11px] line-clamp-1" style="color:#6b7280" itemprop="jobTitle">
                                    {{ $teacher->subject }}
                                </p>
                            @endif

                            {{-- Kontak --}}
                            @if($teacher->phone || $teacher->email || $teacher->whatsapp)
                                <div class="mt-3 pt-3 border-t flex flex-wrap gap-1.5" style="border-color:#f3f4f6">
                                    @if($teacher->phone)
                                        <a href="tel:{{ $teacher->phone }}"
                                           onclick="event.stopPropagation()"
                                           title="Telepon: {{ $teacher->phone }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[10px] font-semibold border transition-all hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700"
                                           style="border-color:#e5e7eb;color:#6b7280">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            Telepon
                                        </a>
                                    @endif
                                    @if($teacher->email)
                                        <a href="mailto:{{ $teacher->email }}"
                                           onclick="event.stopPropagation()"
                                           title="{{ $teacher->email }}"
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[10px] font-semibold border transition-all hover:bg-amber-50 hover:border-amber-400 hover:text-amber-700"
                                           style="border-color:#e5e7eb;color:#6b7280">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Email
                                        </a>
                                    @endif
                                    @if($teacher->whatsapp)
                                        <a href="https://wa.me/{{ $teacher->whatsapp }}"
                                           onclick="event.stopPropagation()"
                                           target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[10px] font-semibold border transition-all hover:bg-green-50 hover:border-green-400 hover:text-green-700"
                                           style="border-color:#e5e7eb;color:#6b7280">
                                            <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            WA
                                        </a>
                                    @endif
                                </div>
                            @endif

                            {{-- Link ke profil lengkap --}}
                            <a href="{{ route('teachers.show', $teacher) }}"
                               class="mt-3 text-[11px] font-semibold text-amber-600 hover:text-amber-700 hover:underline inline-flex items-center gap-1 mt-auto pt-2">
                                Lihat Profil
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── Pagination ─────────────────────────────── --}}
            @if($teachers->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $teachers->links('pagination::simple-tailwind') }}
                </div>
            @endif
        @endif

    </div>

@endsection
