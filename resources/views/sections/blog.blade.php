<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section id="blog" class="mb-12 border-t pt-12" style="border-color:var(--border)">
        <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
            <div>
                <div class="fi-label mb-2">Berita & Artikel</div>
                <h2 class="text-2xl font-bold" style="color:var(--text)">Blog Sekolah</h2>
                <p class="mt-1 text-sm" style="color:var(--muted)">Informasi terkini, prestasi, dan cerita inspiratif dari komunitas sekolah.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 shrink-0">
                Semua Artikel <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($posts->isNotEmpty())
            @php $featured = $posts->first(); @endphp

            {{-- Featured post --}}
            <article class="fi-card overflow-hidden mb-5" data-aos="fade-up" data-aos-delay="50">
                <div class="grid lg:grid-cols-5">
                    <a href="{{ route('blog.show', $featured->slug) }}"
                       class="lg:col-span-2 h-52 lg:h-auto relative overflow-hidden block group">
                        <img src="{{ $featured->thumbnail_url }}"
                             alt="{{ $featured->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent"></div>
                    </a>
                    <div class="lg:col-span-3 p-6 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">{{ $featured->category }}</span>
                            <span class="text-xs" style="color:var(--muted)">{{ $featured->formatted_date }} · {{ $featured->read_time }} menit baca</span>
                        </div>
                        <h3 class="text-lg font-bold leading-snug mb-2" style="color:var(--text)">
                            <a href="{{ route('blog.show', $featured->slug) }}" class="hover:text-amber-700 transition-colors">
                                {{ $featured->title }}
                            </a>
                        </h3>
                        <p class="text-sm leading-relaxed mb-5 line-clamp-3" style="color:var(--muted)">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $featured->author_initials }}</div>
                                <span class="text-xs font-medium" style="color:var(--muted)">{{ $featured->author }}</span>
                            </div>
                            <a href="{{ route('blog.show', $featured->slug) }}" class="btn-primary text-xs">
                                Baca Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Post grid --}}
            @if($posts->count() > 1)
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts->skip(1) as $post)
                        <article class="fi-card fi-card-hover group flex flex-col overflow-hidden"
                                 data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                            <a href="{{ route('blog.show', $post->slug) }}" class="relative h-44 block overflow-hidden">
                                <img src="{{ $post->thumbnail_url }}"
                                     alt="{{ $post->title }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute top-3 left-3">
                                    <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md border backdrop-blur-sm bg-white/80 text-amber-700 border-amber-200">{{ $post->category }}</span>
                                </div>
                            </a>
                            <div class="p-5 flex flex-col flex-1">
                                <span class="text-[11px] mb-2 block" style="color:var(--muted)">{{ $post->formatted_date }}</span>
                                <h3 class="font-semibold text-sm leading-snug mb-2 line-clamp-2 hover:text-amber-700 transition-colors" style="color:var(--text)">
                                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="text-xs leading-relaxed flex-1 line-clamp-3" style="color:var(--muted)">{{ $post->excerpt }}</p>
                                <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 text-xs font-semibold text-amber-600 hover:underline inline-flex items-center gap-1">
                                    Baca Selengkapnya
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-16 fi-card">
                <div class="text-5xl mb-4">📰</div>
                <p class="text-sm font-medium" style="color:var(--muted)">Belum ada artikel yang dipublikasikan.</p>
            </div>
        @endif

        <div class="mt-8 text-center">
            <a href="{{ route('blog.index') }}" class="btn-outline">Lihat Semua Artikel</a>
        </div>
    </section>
</div>
