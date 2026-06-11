@if(isset($programs) && $programs->isNotEmpty())
<section id="program" class="py-20 sm:py-28" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex items-end justify-between gap-6 mb-14" data-aos="fade-up">
            <div>
                <div class="fi-label mb-3">Program Unggulan</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">Program Sekolah</h2>
                <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                    Beragam program pembelajaran dan pengembangan diri untuk membentuk siswa yang unggul dan berkarakter.
                </p>
            </div>
            <a href="{{ route('programs.index') }}"
               class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Semua Program
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($programs as $program)
                <a href="{{ route('programs.show', $program->slug) }}"
                   class="fi-card fi-card-hover group flex flex-col overflow-hidden"
                   data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">

                    <div class="p-7 flex flex-col flex-1">
                        <div class="flex items-center gap-4 mb-3">
                            @if($program->icon)
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0"
                                     style="background:var(--color-amber-100)">
                                    {{ $program->icon }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <h3 class="font-bold text-base leading-snug line-clamp-2 group-hover:opacity-75 transition-opacity"
                                    style="color:var(--text)">{{ $program->title }}</h3>
                                @if($program->category)
                                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full border mt-1.5"
                                          style="background:var(--color-amber-50);color:var(--color-amber-800);border-color:var(--color-amber-200)">
                                        {{ $program->category }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($program->excerpt)
                            <p class="text-sm leading-relaxed line-clamp-3 mb-5 flex-1" style="color:var(--muted)">
                                {{ $program->excerpt }}
                            </p>
                        @endif

                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold mt-auto hover:opacity-75 transition-opacity"
                              style="color:var(--primary)">
                            Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('programs.index') }}" class="btn-outline">Lihat Semua Program</a>
        </div>
    </div>
</section>
@endif
