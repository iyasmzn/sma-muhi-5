@if($contactItems->isNotEmpty())
<section id="kontak-section" class="py-20" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f2236 100%)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-14" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 bg-amber-400/15 border border-amber-400/30 text-amber-300 px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-widest mb-5">
                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                Hubungi Kami
            </div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight mb-4">
                Kami Siap Membantu Anda
            </h2>
            <p class="text-white/60 text-base max-w-xl mx-auto leading-relaxed">
                Punya pertanyaan seputar SPMB, akademik, atau kegiatan sekolah? Jangan ragu untuk menghubungi kami.
            </p>
        </div>

        {{-- Contact Cards Grid --}}
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-{{ min($contactItems->count(), 3) }} xl:grid-cols-{{ min($contactItems->count(), 4) }}">
            @foreach($contactItems as $ci)
                <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6 transition-all duration-300 hover:bg-white/10 hover:border-amber-400/40 hover:shadow-lg hover:shadow-amber-500/10"
                     data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">

                    {{-- Glow on hover --}}
                    <div class="absolute inset-0 rounded-2xl bg-linear-to-br from-amber-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                    {{-- Icon --}}
                    <div class="w-12 h-12 rounded-xl bg-amber-400/10 border border-amber-400/20 flex items-center justify-center text-2xl mb-4 group-hover:bg-amber-400/20 transition-colors duration-300">
                        {{ $ci->icon }}
                    </div>

                    {{-- Content --}}
                    <div class="fi-label text-amber-400/80 mb-1 text-[11px]">{{ $ci->label }}</div>
                    <div class="text-white/85 text-sm font-medium leading-relaxed mb-4">{{ $ci->value }}</div>

                    {{-- Link button --}}
                    @if($ci->link)
                        <a href="{{ $ci->link }}"
                           target="{{ str_starts_with($ci->link, 'http') ? '_blank' : '_self' }}"
                           rel="{{ str_starts_with($ci->link, 'http') ? 'noopener noreferrer' : '' }}"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 hover:text-amber-300 transition-colors group/link">
                            Buka
                            <svg class="w-3.5 h-3.5 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Google Maps Embed --}}
        @if(setting('contact_map_url') && str_starts_with(setting('contact_map_url'), 'https://www.google.com/maps/embed'))
        <div class="mt-12" data-aos="fade-up" data-aos-delay="150">
            <div class="relative overflow-hidden rounded-2xl border border-white/10 shadow-2xl shadow-black/40">
                {{-- Header bar --}}
                <div class="flex items-center gap-3 px-5 py-3 bg-white/5 border-b border-white/10">
                    <div class="w-8 h-8 rounded-lg bg-amber-400/15 border border-amber-400/25 flex items-center justify-center text-base">📍</div>
                    <div>
                        <div class="text-white/90 text-sm font-semibold">Lokasi Sekolah</div>
                        @if(setting('contact_address'))
                            <div class="text-white/45 text-xs truncate max-w-sm">{{ setting('contact_address') }}</div>
                        @endif
                    </div>
                    <a href="{{ str_replace('/embed?', '/search?', setting('contact_map_url')) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="ms-auto inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 hover:text-amber-300 transition-colors">
                        Buka di Maps
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>
                {{-- Map iframe --}}
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
        @endif

        {{-- Bottom CTA --}}
        <div class="mt-14 text-center" data-aos="fade-up" data-aos-delay="200">
            <p class="text-white/50 text-sm mb-5">Atau kunjungi langsung kantor kami pada hari dan jam operasional.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                @if(setting('social_whatsapp'))
                    <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-green-500 text-white font-semibold text-sm hover:bg-green-400 transition shadow-lg shadow-green-500/20">
                        💬 Chat WhatsApp
                    </a>
                @endif
                @if(setting('contact_email'))
                    <a href="mailto:{{ setting('contact_email') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/10 border border-white/20 text-white font-semibold text-sm hover:bg-white/20 transition">
                        ✉️ Kirim Email
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
