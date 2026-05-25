<section class="relative h-130 sm:h-145 lg:h-160 overflow-hidden -mt-17">

    {{-- Slides (server-rendered, Alpine mengontrol visibilitas) --}}
    @forelse($slides as $index => $s)
        <div class="slide absolute inset-0 transition-opacity duration-700"
             :class="{{ $index }} === slide ? 'opacity-100 z-10' : 'opacity-0 z-0'">

            {{-- Background image --}}
            <img src="{{ $s->image_url }}"
                 alt="{{ $s->title }}"
                 class="absolute inset-0 w-full h-full object-cover"
                 loading="{{ $index === 0 ? 'eager' : 'lazy' }}">

            {{-- Dark gradient overlay --}}
            <div class="absolute inset-0"
                 style="background:linear-gradient(135deg,rgba(0,0,0,.65) 0%,rgba(0,0,0,.35) 60%,rgba(0,0,0,.15) 100%)"></div>

            {{-- Content --}}
            <div class="relative z-10 h-full flex items-center">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                    <div class="max-w-2xl text-white">
                        <div class="inline-flex items-center gap-2 bg-white/15 border border-white/25 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm mb-5">
                            <span class="w-2 h-2 bg-amber-300 rounded-full animate-pulse"></span>
                            <span class="opacity-90 text-sm font-medium">{{ $index + 1 }} / {{ $slides->count() }}</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.12] tracking-tight mb-4">
                            {{ $s->title }}
                        </h1>
                        @if($s->subtitle)
                            <p class="text-white/80 text-base sm:text-lg leading-relaxed mb-8 max-w-lg">
                                {{ $s->subtitle }}
                            </p>
                        @endif
                        <div class="flex flex-wrap gap-3">
                            @if($s->button_label && $s->button_url)
                                <a href="{{ $s->button_url }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-400 text-amber-900 font-bold hover:bg-amber-300 transition shadow-lg shadow-amber-500/30 text-sm">
                                    {{ $s->button_label }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            @endif
                            <a href="#profil"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/15 border border-white/30 backdrop-blur-sm font-semibold hover:bg-white/25 transition text-sm">
                                Profil Sekolah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{-- Fallback jika belum ada slide --}}
        <div class="absolute inset-0 bg-gradient-to-br from-amber-900 to-amber-700 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl font-extrabold mb-3">{{ setting('site_name', config('app.name')) }}</h1>
                <p class="text-white/80">{{ setting('site_tagline', 'Unggul, Berkarakter, Berprestasi') }}</p>
            </div>
        </div>
    @endforelse

    {{-- Dot indicators --}}
    @if($slides->count() > 1)
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            @foreach($slides as $index => $s)
                <button @click="slide = {{ $index }}"
                        class="transition-all duration-300 rounded-full"
                        :class="{{ $index }} === slide ? 'w-6 h-2.5 bg-amber-400' : 'w-2.5 h-2.5 bg-white/50 hover:bg-white/80'"></button>
            @endforeach
        </div>
    @endif

    {{-- Prev / Next arrows --}}
    @if($slides->count() > 1)
        <button @click="slide = (slide - 1 + total) % total"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm text-white flex items-center justify-center transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="slide = (slide + 1) % total"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm text-white flex items-center justify-center transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>
    @endif

    {{-- Bottom wave --}}
    <div class="absolute bottom-0 inset-x-0 z-10">
        <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-14">
            <path d="M0 56L80 48C160 40 320 24 480 22.7C640 21.3 800 34.7 960 40C1120 45.3 1280 42.7 1360 41.3L1440 40V56H0Z" fill="var(--bg)"/>
        </svg>
    </div>
</section>
