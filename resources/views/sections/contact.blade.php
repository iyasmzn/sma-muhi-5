@if(setting('contact_map_url') && str_starts_with(setting('contact_map_url'), 'https://www.google.com/maps/embed'))
<section id="lokasi-section" class="py-24 sm:py-32"
         style="background:linear-gradient(135deg,#0a0a0b 0%,#1a1a1e 50%,#0a0f1a 100%)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border text-xs font-bold uppercase tracking-widest mb-5"
                 style="background:rgba(217,119,6,.12);border-color:rgba(217,119,6,.3);color:var(--color-amber-300)">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse inline-block"></span>
                Lokasi Kami
            </div>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight tracking-tight mb-4">
                Temukan Kami di Sini
            </h2>
            <p class="text-white/50 text-base max-w-xl mx-auto leading-relaxed">
                Kunjungi langsung sekolah kami. Informasi kontak lengkap tersedia di bagian bawah halaman.
            </p>
        </div>

        {{-- Google Maps --}}
        <div data-aos="fade-up" data-aos-delay="150">
            <div class="relative overflow-hidden rounded-3xl border shadow-2xl shadow-black/40"
                 style="border-color:rgba(255,255,255,.08)">
                <div class="flex items-center gap-3 px-6 py-4 border-b"
                     style="background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.08)">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg"
                         style="background:rgba(217,119,6,.12);border:1px solid rgba(217,119,6,.2)">📍</div>
                    <div>
                        <div class="text-white/90 text-sm font-semibold">Lokasi Sekolah</div>
                        @if(setting('contact_address'))
                            <div class="text-white/40 text-xs truncate max-w-sm">{{ setting('contact_address') }}</div>
                        @endif
                    </div>
                    <a href="{{ str_replace('/embed?', '/search?', setting('contact_map_url')) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="ms-auto inline-flex items-center gap-1.5 text-xs font-semibold transition-opacity hover:opacity-75"
                       style="color:var(--color-amber-400)">
                        Buka di Maps
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
                <iframe
                    src="{{ setting('contact_map_url') }}"
                    width="100%"
                    height="400"
                    style="border:0;display:block;"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi {{ setting('site_name') }}"
                ></iframe>
            </div>
        </div>
    </div>
</section>
@endif
