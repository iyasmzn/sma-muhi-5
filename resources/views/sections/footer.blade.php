@php
    // Self-contained: footer reads every value from settings so it stays in sync
    // with the admin panel (Pengaturan Umum, Navbar, Tautan Cepat) on every page.
    $footerNavItems = collect(json_decode(setting('nav_items', ''), true) ?: [
        ['label' => 'Beranda', 'url' => '/',        'target' => '_self', 'is_active' => true],
        ['label' => 'Galeri',  'url' => '/galeri',  'target' => '_self', 'is_active' => true],
        ['label' => 'Guru',    'url' => '/guru',    'target' => '_self', 'is_active' => true],
        ['label' => 'Blog',    'url' => '/blog',    'target' => '_self', 'is_active' => true],
        ['label' => 'Unduhan', 'url' => '/unduhan', 'target' => '_self', 'is_active' => true],
        ['label' => 'Kontak',  'url' => '#kontak',  'target' => '_self', 'is_active' => true],
    ])->where('is_active', true)->values();

    $footerQuickLinks = collect(json_decode(setting('quick_links', ''), true) ?: [])
        ->where('is_active', true)->values();

    // Each social only renders when its setting is filled. SVG paths share a 24×24 viewBox.
    $footerSocials = collect([
        ['url' => setting('social_facebook'),  'label' => 'Facebook',  'd' => 'M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07c0 6.02 4.39 11.01 10.13 11.93v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.25h3.33l-.53 3.49h-2.8v8.44C19.61 23.08 24 18.09 24 12.07z'],
        ['url' => setting('social_instagram'), 'label' => 'Instagram', 'd' => 'M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.7 3.7 0 0 1-1.38-.9 3.7 3.7 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 3.68A6.16 6.16 0 1 0 12 18.16 6.16 6.16 0 0 0 12 5.84zm0 10.16A4 4 0 1 1 12 8a4 4 0 0 1 0 8zm6.4-10.4a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88z'],
        ['url' => setting('social_youtube'),   'label' => 'YouTube',   'd' => 'M23.5 6.2a3.02 3.02 0 0 0-2.12-2.14C19.5 3.55 12 3.55 12 3.55s-7.5 0-9.38.51A3.02 3.02 0 0 0 .5 6.2C0 8.08 0 12 0 12s0 3.92.5 5.8a3.02 3.02 0 0 0 2.12 2.14c1.88.51 9.38.51 9.38.51s7.5 0 9.38-.51a3.02 3.02 0 0 0 2.12-2.14C24 15.92 24 12 24 12s0-3.92-.5-5.8zM9.55 15.57V8.43L15.82 12l-6.27 3.57z'],
        ['url' => ($wa = setting('social_whatsapp')) ? 'https://wa.me/'.$wa : null, 'label' => 'WhatsApp', 'd' => 'M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.22 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2-1.41.25-.69.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35zM12.04 21.5h-.01a9.5 9.5 0 0 1-4.84-1.33l-.35-.2-3.6.94.96-3.5-.23-.36a9.49 9.49 0 1 1 8.07 4.45zM20.52 3.48A11.86 11.86 0 0 0 12.04 0C5.46 0 .1 5.36.1 11.95c0 2.1.55 4.16 1.6 5.97L0 24l6.24-1.64a11.9 11.9 0 0 0 5.8 1.48h.01c6.58 0 11.94-5.36 11.94-11.95a11.86 11.86 0 0 0-3.47-8.41z'],
    ])->filter(fn ($s) => ! empty($s['url']))->values();

    $footerHasContact = setting('contact_address') || setting('contact_phone') || setting('contact_email') || setting('contact_hours');
@endphp

<footer id="kontak" class="mt-20" style="background:#1d1d1f">
    <div class="amber-bar"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-12">

            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-4">
                <div class="flex items-center gap-2.5 mb-4">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}"
                             alt="{{ setting('site_name', config('app.name')) }}"
                             class="w-10 h-10 rounded-2xl object-contain shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow shrink-0" style="background:var(--primary)">
                            <span class="text-white font-extrabold">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name')) }}</div>
                        <div class="text-[10px] text-amber-500 font-semibold uppercase tracking-widest mt-0.5">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                    </div>
                </div>

                <p class="text-xs leading-relaxed text-white/45 mb-5 max-w-xs">{{ setting('site_description', 'Mencetak generasi penerus bangsa yang cerdas, berkarakter mulia, dan siap menghadapi tantangan masa depan.') }}</p>

                @if($footerSocials->isNotEmpty())
                    <div class="flex gap-2">
                        @foreach($footerSocials as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                               aria-label="{{ $social['label'] }}"
                               class="w-9 h-9 rounded-xl border border-white/10 bg-white/5 flex items-center justify-center text-white/55 transition-all hover:text-white hover:border-amber-400/50 hover:bg-amber-500/10">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $social['d'] }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Menu utama (dynamic: nav_items) --}}
            <div class="lg:col-span-3">
                <div class="text-[11px] font-bold uppercase tracking-widest text-amber-500 mb-4">Menu</div>
                <ul class="space-y-2.5">
                    @foreach($footerNavItems as $item)
                        <li>
                            <a href="{{ str_starts_with($item['url'], '#') ? '/'.$item['url'] : $item['url'] }}"
                               target="{{ $item['target'] ?? '_self' }}"
                               class="text-sm text-white/55 hover:text-white transition-colors">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Tautan cepat (dynamic: quick_links) --}}
            @if($footerQuickLinks->isNotEmpty())
                <div class="lg:col-span-2">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-amber-500 mb-4">Tautan Cepat</div>
                    <ul class="space-y-2.5">
                        @foreach($footerQuickLinks as $link)
                            <li>
                                <a href="{{ str_starts_with($link['url'], '#') ? '/'.$link['url'] : $link['url'] }}"
                                   class="text-sm text-white/55 hover:text-white transition-colors">{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Kontak (dynamic: contact_*) --}}
            @if($footerHasContact)
                <div class="lg:col-span-3">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-amber-500 mb-4">Kontak</div>
                    <ul class="space-y-3 text-sm text-white/55">
                        @if(setting('contact_address'))
                            <li class="flex gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ setting('contact_address') }}</span>
                            </li>
                        @endif
                        @if(setting('contact_phone'))
                            <li class="flex gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}" class="hover:text-white transition-colors">{{ setting('contact_phone') }}</a>
                            </li>
                        @endif
                        @if(setting('contact_email'))
                            <li class="flex gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:{{ setting('contact_email') }}" class="hover:text-white transition-colors break-all">{{ setting('contact_email') }}</a>
                            </li>
                        @endif
                        @if(setting('contact_hours'))
                            <li class="flex gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-amber-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ setting('contact_hours') }}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>

        {{-- Bottom bar --}}
        <div class="mt-12 pt-7 border-t border-white/10">
            <p class="text-xs text-white/30 text-center">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Semua hak dilindungi.</p>
        </div>
    </div>
</footer>
