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
        <meta property="og:image"       content="{{ $seo['og_image'] }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height"content="630">
        <meta property="og:image:alt"   content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
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

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        :root {
            --bg:     #f9fafb; --card:   #ffffff; --border: #e5e7eb;
            --text:   #030712; --muted:  #6b7280;

            /* ── Warna utama dari pengaturan admin ────────────────────── */
            --primary: {{ setting('theme_primary_color', '#d97706') }};

            /* ── Override seluruh Tailwind amber palette dengan --primary ─
               Karena Tailwind v4 mendefinisikan warna dalam @layer theme,
               deklarasi non-layered di sini selalu menang (cascade layer rules).
               color-mix(in oklab, ...) menghasilkan shades yang perceptually uniform. */
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
        body { background:var(--bg); color:var(--text); font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif; }
        .fi-card { background:var(--card); border:1px solid var(--border); border-radius:.75rem; box-shadow:0 1px 2px 0 rgb(0 0 0/.05); transition:box-shadow .15s,border-color .15s; }
        .fi-card-hover:hover { box-shadow:0 6px 20px rgb(0 0 0/.1); border-color:var(--color-amber-300); }
        .btn-primary { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.25rem; border-radius:.5rem; font-size:.875rem; font-weight:600; background:var(--primary); color:#fff; transition:background .15s; }
        .btn-primary:hover { background:var(--color-amber-700); }
        .btn-outline { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.25rem; border-radius:.5rem; font-size:.875rem; font-weight:600; border:1px solid var(--border); color:var(--text); background:var(--card); transition:border-color .15s,color .15s; }
        .btn-outline:hover { border-color:var(--primary); color:var(--primary); }
        .fi-label { font-size:.6875rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--primary); }
        .amber-bar { height:3px; background:linear-gradient(90deg, var(--primary), color-mix(in oklab, var(--primary) 55%, white) 60%, transparent); }

        /* Prose content */
        .prose h2 { font-size:1.25rem; font-weight:700; margin-top:1.75rem; margin-bottom:.75rem; color:var(--text); }
        .prose h3 { font-size:1.1rem; font-weight:600; margin-top:1.5rem; margin-bottom:.5rem; color:var(--text); }
        .prose p  { margin-bottom:1rem; line-height:1.8; color:var(--muted); }
        .prose ul { list-style:disc; padding-left:1.5rem; margin-bottom:1rem; color:var(--muted); }
        .prose ol { list-style:decimal; padding-left:1.5rem; margin-bottom:1rem; color:var(--muted); }
        .prose li { margin-bottom:.4rem; line-height:1.7; }
        .prose blockquote { border-left:3px solid var(--primary); padding-left:1rem; margin:1.5rem 0; color:var(--muted); font-style:italic; }
        .prose a  { color:var(--primary); text-decoration:underline; text-underline-offset:2px; }
        .prose strong { color:var(--text); }
    </style>

    @stack('head')
</head>

<body class="min-h-screen antialiased">

    {{-- ── Navbar ──────────────────────────────────────────── --}}
    <header class="border-b" style="background:var(--card);border-color:var(--border)">
        <div class="amber-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-4">
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-8 h-8 rounded-lg object-contain">
                @else
                    <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div class="hidden sm:block leading-tight">
                    <div class="font-bold text-sm" style="color:var(--text)">{{ setting('site_name', config('app.name')) }}</div>
                    <div class="text-[10px] text-amber-600 font-semibold uppercase tracking-wider">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                </div>
            </a>

            <div class="flex items-center gap-1">
                @php
                    $pubNavItems = collect(json_decode(setting('nav_items', ''), true) ?: [
                        ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true],
                        ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true],
                        ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true],
                        ['label' => 'Unduhan',  'url' => '/unduhan',  'target' => '_self', 'is_active' => true],
                        ['label' => 'Kontak',   'url' => '/#kontak',  'target' => '_self', 'is_active' => true],
                    ])->where('is_active', true)->values();
                @endphp
                @foreach($pubNavItems as $item)
                    @php
                        // Anchor-only links (#section) dikonversi ke /#section agar bekerja dari halaman mana pun
                        $navUrl = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];

                        // Cek apakah link ini aktif: cocokkan path URL (abaikan anchor & query)
                        $navPath = parse_url($navUrl, PHP_URL_PATH) ?? '/';
                        $isActive = $navPath === '/'
                            ? request()->is('/')
                            : request()->is(ltrim($navPath, '/'), ltrim($navPath, '/') . '/*');
                    @endphp
                    <a href="{{ $navUrl }}" target="{{ $item['target'] ?? '_self' }}"
                       class="text-xs font-medium px-3 py-1.5 rounded-lg transition-colors
                              {{ $isActive ? 'bg-amber-50 text-amber-700 font-semibold' : 'hover:bg-amber-50 hover:text-amber-700' }}"
                       @if($isActive) aria-current="page" @endif
                       style="{{ $isActive ? '' : 'color:var(--muted)' }}">{{ $item['label'] }}</a>
                @endforeach
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-xs ml-1">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline text-xs ml-1">Masuk</a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    {{-- ── Page Content ─────────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────── --}}
    <footer class="border-t mt-16" style="background:var(--card);border-color:var(--border)">
        <div class="amber-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-7 h-7 rounded-lg object-contain">
                @else
                    <div class="w-7 h-7 rounded-lg bg-amber-500 flex items-center justify-center">
                        <span class="text-white font-bold text-xs">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <span class="text-sm font-semibold" style="color:var(--text)">{{ setting('site_name', config('app.name')) }}</span>
            </div>
            <p class="text-xs" style="color:var(--muted)">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Semua hak dilindungi.</p>
            <nav class="flex gap-4">
                @foreach($pubNavItems as $item)
                    <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                       class="text-xs hover:text-amber-600 transition-colors"
                       style="color:var(--muted)">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init({ once: true, duration: 650, easing: 'ease-out-quad', offset: 50 });</script>
</body>
</html>
