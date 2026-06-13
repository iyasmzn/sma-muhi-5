@php
    $galleryMedia ??= collect();

    $lightboxItems = $galleryMedia->map(fn ($m) => [
        'type' => $m->is_embed ? 'video' : 'image',
        'name' => $m->name,
        'src'  => $m->is_embed ? null : $m->url,
        'html' => $m->is_embed ? (string) $m->embed_html : null,
        'vertical' => in_array($m->embed_provider, ['tiktok', 'instagram'], true),
    ])->values();
@endphp

<style>
    [x-cloak] { display: none !important; }

    .glr-card {
        transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
    }
    .glr-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 50px rgba(0,0,0,.16);
        border-color: var(--color-amber-200);
    }
    .glr-card:hover .glr-card-img { transform: scale(1.07); }
    .glr-card-img { transition: transform .7s ease; }

    /* Caption fades & slides up on hover */
    .glr-cap {
        transition: transform .35s ease, opacity .35s ease;
        transform: translateY(10px);
        opacity: 0;
    }
    .glr-card:hover .glr-cap { transform: translateY(0); opacity: 1; }
    .glr-card:hover .glr-overlay { opacity: 1; }
    @media (hover: none) {
        .glr-cap { transform: none; opacity: 1; }
        .glr-overlay { opacity: 1; }
    }
</style>

<section id="galeri" class="py-20 sm:py-28" style="background:var(--bg)"
         x-data="{
            open: false,
            active: null,
            items: @js($lightboxItems),
            show(i) { this.active = i; this.open = true; document.body.style.overflow = 'hidden'; },
            close() { this.open = false; this.active = null; document.body.style.overflow = ''; },
            get current() { return this.active !== null ? this.items[this.active] : null; },
         }"
         @keydown.escape.window="close()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex items-end justify-between gap-6 mb-12 sm:mb-14" data-aos="fade-up">
            <div>
                <div class="fi-label mb-3">Foto & Video</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">Galeri & Kegiatan Sekolah</h2>
                <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                    Momen-momen berharga dari kehidupan sekolah kami.
                </p>
            </div>
            <a href="{{ route('gallery.index') }}"
               class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Semua Foto
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($galleryMedia->isEmpty())
            <div class="rounded-2xl border border-dashed py-16 text-center" style="border-color:var(--border)" data-aos="fade-up">
                <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-4 text-3xl">🖼️</div>
                <p class="text-sm font-semibold" style="color:var(--text)">Belum ada foto di galeri</p>
                <p class="text-xs mt-1" style="color:var(--muted)">Tandai media dengan "Tampil di Galeri" dari panel admin.</p>
            </div>
        @else
            {{-- Neat, uniform image cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">
                @foreach($galleryMedia as $item)
                    <a href="{{ $item->is_embed ? $item->embed_url : $item->url }}"
                       @if($item->is_embed) target="_blank" rel="noopener" @endif
                       @click.prevent="show({{ $loop->index }})"
                       class="glr-card group relative block overflow-hidden rounded-2xl border shadow-sm cursor-pointer"
                       style="border-color:var(--border)"
                       data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}" data-aos-duration="500">

                        <div class="aspect-4/3 overflow-hidden bg-gray-100">
                            <img src="{{ $item->thumbnail_url }}"
                                 alt="{{ $item->alt ?? $item->name }}"
                                 loading="lazy"
                                 class="glr-card-img w-full h-full object-cover">
                        </div>

                        {{-- Gradient overlay --}}
                        <div class="glr-overlay absolute inset-0 bg-linear-to-t from-black/75 via-black/10 to-transparent opacity-60 transition-opacity duration-300"></div>

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

                        {{-- Caption (fades up on hover) --}}
                        <div class="glr-cap absolute inset-x-0 bottom-0 p-3 sm:p-4">
                            <h3 class="text-white text-xs sm:text-sm font-semibold leading-snug">
                                {{ $item->name }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

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
            <template x-if="current && current.type === 'video'">
                <div class="mx-auto rounded-xl overflow-hidden shadow-2xl"
                     :style="current.vertical ? 'max-width:min(64vh, 90vw)' : ''"
                     x-html="current.html"></div>
            </template>
            <p class="text-center text-white/80 text-sm mt-4 font-medium" x-text="current ? current.name : ''"></p>
        </div>
    </div>
</section>
