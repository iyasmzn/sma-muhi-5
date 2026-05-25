<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section id="profil" class="mb-10">
        @if($stats->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-{{ min($stats->count(), 4) }} gap-4">
                @foreach($stats as $stat)
                    <div class="fi-card fi-card-hover p-5 text-center"
                         data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="text-3xl mb-2">{{ $stat->icon }}</div>
                        <div class="text-xl font-extrabold text-amber-600">{{ $stat->value }}</div>
                        <div class="text-xs font-semibold mt-0.5" style="color:var(--text)">{{ $stat->label }}</div>
                        @if($stat->sub)
                            <div class="text-[11px] mt-0.5" style="color:var(--muted)">{{ $stat->sub }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
