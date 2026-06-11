<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ── Core SEO ────────────────────────────────────────── --}}
    <title>{{ $seo['title'] ?? setting('site_name', config('app.name')) }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">

    {{-- ── Favicon ─────────────────────────────────────────── --}}
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    {{-- ── Open Graph ──────────────────────────────────────── --}}
    <meta property="og:type"        content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:title"       content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url"         content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:site_name"   content="{{ setting('site_name', config('app.name')) }}">
    @if(!empty($seo['og_image']))
        <meta property="og:image"        content="{{ $seo['og_image'] }}">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt"    content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    @endif
    @if(!empty($seo['published']))
        <meta property="article:published_time" content="{{ $seo['published'] }}">
    @endif
    @if(!empty($seo['author']))
        <meta property="article:author" content="{{ $seo['author'] }}">
    @endif
    @if(!empty($seo['category']))
        <meta property="article:section" content="{{ $seo['category'] }}">
    @endif

    {{-- ── Twitter Card ────────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    @if(!empty($seo['og_image']))
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif

    {{-- ── Structured Data (JSON-LD) ──────────────────────── --}}
    @stack('structured-data')

    {{-- ── Fonts & Assets ─────────────────────────────────── --}}
    @fonts

    {{-- Dynamic Google Font loading based on admin setting --}}
    @php
        $fontMap = [
            'instrument-sans'   => ['family' => "'Instrument Sans', ui-sans-serif, system-ui, sans-serif", 'google' => null],
            'inter'             => ['family' => "'Inter', ui-sans-serif, system-ui, sans-serif",             'google' => 'Inter:wght@300;400;500;600;700;800;900'],
            'plus-jakarta-sans' => ['family' => "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif", 'google' => 'Plus+Jakarta+Sans:wght@300;400;500;600;700;800'],
            'outfit'            => ['family' => "'Outfit', ui-sans-serif, system-ui, sans-serif",            'google' => 'Outfit:wght@300;400;500;600;700;800;900'],
            'dm-sans'           => ['family' => "'DM Sans', ui-sans-serif, system-ui, sans-serif",           'google' => 'DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400'],
            'nunito'            => ['family' => "'Nunito', ui-sans-serif, system-ui, sans-serif",            'google' => 'Nunito:wght@300;400;500;600;700;800'],
            'poppins'           => ['family' => "'Poppins', ui-sans-serif, system-ui, sans-serif",           'google' => 'Poppins:wght@300;400;500;600;700;800'],
            'sora'              => ['family' => "'Sora', ui-sans-serif, system-ui, sans-serif",              'google' => 'Sora:wght@300;400;500;600;700;800'],
        ];
        $selectedFont = setting('theme_font', 'instrument-sans');
        $font = $fontMap[$selectedFont] ?? $fontMap['instrument-sans'];
    @endphp
    @if($font['google'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ $font['google'] }}&display=swap">
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        /* ── Design tokens — Apple-inspired ─────────────────────── */
        :root {
            --bg:     #f5f5f7;
            --bg-alt: #ffffff;
            --card:   #ffffff;
            --border: rgba(0,0,0,.08);
            --text:   #1d1d1f;
            --muted:  #6e6e73;

            --primary: {{ setting('theme_primary_color', '#d97706') }};

            --color-amber-50:  color-mix(in oklab, var(--primary)  8%, white);
            --color-amber-100: color-mix(in oklab, var(--primary) 15%, white);
            --color-amber-200: color-mix(in oklab, var(--primary) 28%, white);
            --color-amber-300: color-mix(in oklab, var(--primary) 45%, white);
            --color-amber-400: color-mix(in oklab, var(--primary) 68%, white);
            --color-amber-500: color-mix(in oklab, var(--primary) 85%, white);
            --color-amber-600: var(--primary);
            --color-amber-700: color-mix(in oklab, var(--primary) 78%, black);
            --color-amber-800: color-mix(in oklab, var(--primary) 58%, black);
            --color-amber-900: color-mix(in oklab, var(--primary) 42%, black);
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: {{ $font['family'] }};
            -webkit-font-smoothing: antialiased;
        }

        /* ── Cards — Apple large radius ─────────────────────────── */
        .fi-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            transition: box-shadow .3s ease, transform .3s ease, border-color .3s ease;
        }
        .fi-card-hover:hover {
            box-shadow: 0 20px 60px rgba(0,0,0,.12);
            transform: translateY(-3px);
            border-color: var(--color-amber-200);
        }

        /* ── Buttons ─────────────────────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem;
            border-radius: .875rem;
            font-size: .9375rem; font-weight: 600;
            background: var(--primary); color: #fff;
            transition: background .2s, box-shadow .2s, transform .15s;
            box-shadow: 0 4px 16px color-mix(in oklab, var(--primary) 35%, transparent);
        }
        .btn-primary:hover {
            background: var(--color-amber-700);
            box-shadow: 0 6px 24px color-mix(in oklab, var(--primary) 45%, transparent);
            transform: translateY(-1px);
        }
        .btn-outline {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem;
            border-radius: .875rem;
            font-size: .9375rem; font-weight: 600;
            border: 1.5px solid var(--border);
            color: var(--text); background: var(--card);
            transition: border-color .2s, color .2s, box-shadow .2s, transform .15s;
        }
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
            transform: translateY(-1px);
        }

        /* ── Labels & badges ─────────────────────────────────────── */
        .fi-label {
            font-size: .6875rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--primary);
        }

        /* ── Accent bar ──────────────────────────────────────────── */
        .amber-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--primary), color-mix(in oklab, var(--primary) 55%, white) 60%, transparent);
        }

        /* ── Prose content ───────────────────────────────────────── */
        .prose h2 { font-size: 1.375rem; font-weight: 700; margin-top: 2rem; margin-bottom: .875rem; color: var(--text); }
        .prose h3 { font-size: 1.125rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: .625rem; color: var(--text); }
        .prose p  { margin-bottom: 1.125rem; line-height: 1.85; color: var(--muted); }
        .prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--muted); }
        .prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--muted); }
        .prose li { margin-bottom: .5rem; line-height: 1.7; }
        .prose blockquote { border-left: 3px solid var(--primary); padding-left: 1.25rem; margin: 1.75rem 0; color: var(--muted); font-style: italic; }
        .prose a  { color: var(--primary); text-decoration: underline; text-underline-offset: 2px; }
        .prose strong { color: var(--text); }
    </style>

    @stack('head')
</head>

<body class="min-h-screen antialiased"
      x-data="{ scrolled: false, mobileOpen: false }"
      x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 40, { passive: true })">

    {{-- ── Navigation header — transparent over hero, solid on scroll ──── --}}
    @php
        $pubNavItems = collect(json_decode(setting('nav_items', ''), true) ?: [
            ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true, 'children' => []],
            ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true, 'children' => []],
            ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true, 'children' => []],
            ['label' => 'Unduhan',  'url' => '/unduhan',  'target' => '_self', 'is_active' => true, 'children' => []],
            ['label' => 'Kontak',   'url' => '/#kontak',  'target' => '_self', 'is_active' => true, 'children' => []],
        ])->where('is_active', true)->values();
    @endphp

    <header class="sticky top-0 z-50 transition-all duration-300"
            :style="scrolled
                ? 'background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid #e5e7eb;box-shadow:0 1px 3px 0 rgb(0 0 0/.1)'
                : 'background:transparent;border-bottom:1px solid transparent'">

        {{-- Amber bar — hanya tampil saat scrolled --}}
        <div class="amber-bar overflow-hidden transition-all duration-300"
             :style="scrolled ? 'height:3px;opacity:1' : 'height:0;opacity:0'"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5 shrink-0 min-w-0">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}"
                             alt="{{ setting('site_name', config('app.name')) }}"
                             class="w-9 h-9 rounded-xl object-contain shrink-0">
                    @else
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-amber-500 shadow flex items-center justify-center">
                            <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="leading-tight min-w-0">
                        <div class="font-bold text-sm truncate transition-colors duration-300"
                             :class="scrolled ? 'text-gray-900' : 'text-white'">
                            {{ setting('site_name', config('app.name', 'SMA Negeri 1')) }}
                        </div>
                        <div class="text-[10px] font-medium uppercase tracking-widest transition-colors duration-300"
                             :class="scrolled ? 'text-amber-600' : 'text-amber-300'">
                            {{ setting('site_tagline', 'Unggul · Berkarakter') }}
                        </div>
                    </div>
                </a>

                {{-- Right: nav links + auth + hamburger --}}
                <div class="flex items-center gap-2">

                    {{-- Desktop nav --}}
                    <nav class="hidden lg:flex items-center gap-0.5">
                        @foreach($pubNavItems as $item)
                            @php
                                $children = collect($item['children'] ?? [])->where('is_active', true)->values();
                                $navUrl  = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];
                                $navPath = parse_url($navUrl, PHP_URL_PATH) ?? '/';
                                $isActive = $navPath === '/'
                                    ? request()->is('/')
                                    : request()->is(ltrim($navPath, '/'), ltrim($navPath, '/') . '/*');
                            @endphp
                            @if($children->isNotEmpty())
                                <div x-data="{ dropOpen: false }" class="relative">
                                    <button @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                                            class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1"
                                            :class="scrolled ? 'text-gray-500 hover:bg-amber-50 hover:text-amber-700' : 'text-white/80 hover:text-white hover:bg-white/10'">
                                        {{ $item['label'] }}
                                        <svg class="w-3 h-3 transition-transform duration-200" :class="dropOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="dropOpen"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                                         class="absolute top-full left-0 mt-1 min-w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50 py-1">
                                        @foreach($children as $child)
                                            <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                <span class="w-1 h-1 rounded-full bg-amber-400 shrink-0"></span>
                                                {{ $child['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $navUrl }}" target="{{ $item['target'] ?? '_self' }}"
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                                   :class="scrolled
                                       ? '{{ $isActive ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-500 hover:bg-amber-50 hover:text-amber-700' }}'
                                       : '{{ $isActive ? 'bg-white/10 text-white font-semibold' : 'text-white/80 hover:text-white hover:bg-white/10' }}'"
                                   @if($isActive) aria-current="page" @endif>
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </nav>

                    {{-- Auth + hamburger --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-xs hidden sm:inline-flex">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border transition-all duration-200"
                               :class="scrolled
                                   ? 'border-gray-200 text-gray-700 bg-white hover:border-amber-400 hover:text-amber-700'
                                   : 'border-white/40 text-white hover:bg-white/10'">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary text-xs hidden sm:inline-flex">Daftar SPMB</a>
                            @endif
                        @endauth
                    @endif

                    {{-- Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="lg:hidden w-9 h-9 rounded-lg flex flex-col items-center justify-center gap-1.5 transition-colors"
                            :class="scrolled ? 'hover:bg-amber-50' : 'hover:bg-white/10'"
                            :aria-expanded="mobileOpen" aria-label="Toggle menu">
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? 'rotate-45 translate-y-2' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? 'opacity-0' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? '-rotate-45 -translate-y-2' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                    </button>
                </div>
            </div>
        </div>

    </header>

    {{-- ── Mobile Full-Screen Menu Overlay ──────────────────── --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden fixed inset-0 flex flex-col"
         style="z-index:60; background:linear-gradient(145deg,#0f172a 0%,#1a2744 50%,#0f2236 100%)"
         x-trap.noscroll="mobileOpen">

        {{-- Top bar: logo + close --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 shrink-0">
            <a href="/" @click="mobileOpen = false" class="flex items-center gap-3">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-10 h-10 rounded-xl object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg">
                        <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name', 'SMA Negeri 1')) }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-amber-400">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                </div>
            </a>

            {{-- Close button --}}
            <button @click="mobileOpen = false"
                    class="w-10 h-10 rounded-xl border border-white/20 flex items-center justify-center text-white/70 hover:text-white hover:border-white/40 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 overflow-y-auto px-6 py-6 flex flex-col justify-center gap-1">
            @foreach($pubNavItems as $i => $item)
                @php
                    $children = collect($item['children'] ?? [])->where('is_active', true)->values();
                    $mobileUrl = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];
                    $mobilePath = parse_url($mobileUrl, PHP_URL_PATH) ?? '/';
                    $isMobileActive = $mobilePath === '/'
                        ? request()->is('/')
                        : request()->is(ltrim($mobilePath, '/'), ltrim($mobilePath, '/') . '/*');
                @endphp
                @if($children->isNotEmpty())
                    <div x-data="{ mobileSubOpen: false }">
                        <button @click="mobileSubOpen = !mobileSubOpen"
                                class="group w-full flex items-center gap-4 py-3.5 border-b border-white/8 hover:border-amber-500/50 transition-all duration-200">
                            <span class="text-xs font-bold text-white/25 group-hover:text-amber-500 transition-colors w-6 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight flex-1 text-left">{{ $item['label'] }}</span>
                            <svg class="w-4 h-4 text-white/20 group-hover:text-amber-400 shrink-0 transition-all duration-200"
                                 :class="mobileSubOpen ? 'rotate-90' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="mobileSubOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="pl-10 pb-2 space-y-1">
                            @foreach($children as $child)
                                <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                                   @click="mobileOpen = false"
                                   class="flex items-center gap-2 py-2 text-lg font-medium text-white/55 hover:text-amber-300 transition-colors">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $mobileUrl }}" target="{{ $item['target'] ?? '_self' }}"
                       @click="mobileOpen = false"
                       class="group flex items-center gap-4 py-3.5 border-b border-white/8 hover:border-amber-500/50 transition-all duration-200">
                        <span class="text-xs font-bold w-6 shrink-0 transition-colors {{ $isMobileActive ? 'text-amber-500' : 'text-white/25 group-hover:text-amber-500' }}">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-2xl font-bold tracking-tight transition-colors {{ $isMobileActive ? 'text-amber-400' : 'text-white/70 group-hover:text-white' }}">{{ $item['label'] }}</span>
                        <svg class="w-4 h-4 text-white/20 group-hover:text-amber-400 ml-auto shrink-0 transition-all group-hover:translate-x-1 duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Auth buttons --}}
        <div class="shrink-0 px-6 py-6 border-t border-white/10 space-y-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-400 transition-colors">
                            Daftar SPMB Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endif
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-center w-full py-3 rounded-xl border border-white/25 text-white/80 font-semibold hover:bg-white/10 hover:text-white transition-colors">
                        Masuk
                    </a>
                @endauth
            @endif

            {{-- Tagline --}}
            <p class="text-center text-xs text-white/30 pt-1">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}</p>
        </div>
    </div>

    {{-- ── Page Content ─────────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────── --}}
    <footer class="mt-20" style="background:#1d1d1f">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

            {{-- Top row --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-10 pb-10 border-b border-white/10">
                <div class="flex items-center gap-3">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}"
                             alt="{{ setting('site_name', config('app.name')) }}"
                             class="w-10 h-10 rounded-2xl object-contain">
                    @else
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow"
                             style="background:var(--primary)">
                            <span class="text-white font-extrabold">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name')) }}</div>
                        <div class="text-[10px] text-amber-500 font-semibold uppercase tracking-widest mt-0.5">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                    </div>
                </div>
                <nav class="flex flex-wrap gap-x-6 gap-y-2">
                    @foreach($pubNavItems as $item)
                        <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                           class="text-sm text-white/50 hover:text-white transition-colors">{{ $item['label'] }}</a>
                    @endforeach
                </nav>
            </div>

            {{-- Bottom row --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-white/30">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Semua hak dilindungi.</p>
                <div class="flex gap-5">
                    @foreach(['Kebijakan Privasi', 'Syarat & Ketentuan'] as $l)
                        <a href="#" class="text-xs text-white/30 hover:text-white/60 transition-colors">{{ $l }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init({ once: true, duration: 700, easing: 'ease-out-quart', offset: 60 });</script>
</body>
</html>
