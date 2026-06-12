@if(isset($testimonials) && $testimonials->isNotEmpty())
<section id="kesan-pesan" class="py-20 sm:py-28" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="text-center mb-14" data-aos="fade-up">
            <div class="fi-label mb-3">Kesan & Pesan</div>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">
                Apa Kata Alumni Kami
            </h2>
            <p class="mt-3 text-base max-w-xl mx-auto leading-relaxed" style="color:var(--muted)">
                Cerita dan kesan dari para alumni yang telah menempuh pendidikan bersama kami.
            </p>
        </div>

        <div data-aos="fade-up" data-aos-delay="80"
             x-data="{
                 active: 0,
                 total: {{ $testimonials->count() }},
                 timer: null,
                 start() { if (this.total > 1) { this.timer = setInterval(() => this.next(), 6000); } },
                 stop() { clearInterval(this.timer); },
                 next() { this.active = (this.active + 1) % this.total; },
                 prev() { this.active = (this.active - 1 + this.total) % this.total; },
                 go(i) { this.active = i; },
             }"
             x-init="start()"
             @mouseenter="stop()" @mouseleave="start()">

            <div class="flex items-center gap-3 sm:gap-5">

                {{-- Prev --}}
                @if($testimonials->count() > 1)
                    <button type="button" @click="prev()" aria-label="Sebelumnya"
                            class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-full border flex items-center justify-center transition-all hover:scale-110"
                            style="border-color:var(--border);background:var(--card);color:var(--text)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                @endif

                {{-- Viewport --}}
                <div class="flex-1 overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out"
                         :style="`transform: translateX(-${active * 100}%)`">
                        @foreach($testimonials as $testimonial)
                            <div class="w-full shrink-0 px-1">
                                <figure class="fi-card h-full flex flex-col items-center text-center p-8 sm:p-10 max-w-3xl mx-auto">

                                    {{-- Quote mark --}}
                                    <svg class="w-10 h-10 mb-5 shrink-0" fill="currentColor" viewBox="0 0 24 24"
                                         style="color:var(--color-amber-300)">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                    </svg>

                                    <blockquote class="flex-1">
                                        <p class="text-base sm:text-lg leading-relaxed" style="color:var(--text)">
                                            {{ $testimonial->message }}
                                        </p>
                                    </blockquote>

                                    <figcaption class="mt-7 flex flex-col items-center gap-3">
                                        <img src="{{ $testimonial->photo_url }}"
                                             alt="Foto {{ $testimonial->name }}"
                                             loading="lazy"
                                             class="w-14 h-14 rounded-full object-cover ring-2 ring-amber-100 shrink-0">
                                        <div>
                                            <div class="font-bold text-sm" style="color:var(--text)">{{ $testimonial->name }}</div>
                                            @php
                                                $meta = collect([
                                                    $testimonial->class_year ? 'Angkatan '.$testimonial->class_year : null,
                                                    $testimonial->graduation_year ? 'Lulus '.$testimonial->graduation_year : null,
                                                ])->filter()->implode(' · ');
                                            @endphp
                                            @if($meta)
                                                <div class="text-xs mt-0.5" style="color:var(--muted)">{{ $meta }}</div>
                                            @endif
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Next --}}
                @if($testimonials->count() > 1)
                    <button type="button" @click="next()" aria-label="Berikutnya"
                            class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-full border flex items-center justify-center transition-all hover:scale-110"
                            style="border-color:var(--border);background:var(--card);color:var(--text)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                @endif
            </div>

            {{-- Dot indicators --}}
            @if($testimonials->count() > 1)
                <div class="flex justify-center gap-2 mt-8">
                    @foreach($testimonials as $index => $testimonial)
                        <button type="button" @click="go({{ $index }})" aria-label="Ke testimoni {{ $index + 1 }}"
                                class="transition-all duration-300 rounded-full h-2.5"
                                :class="active === {{ $index }} ? 'w-7' : 'w-2.5'"
                                :style="active === {{ $index }} ? 'background:var(--primary)' : 'background:var(--border)'"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endif
