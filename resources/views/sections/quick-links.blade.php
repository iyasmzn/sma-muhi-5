<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-2 pb-10">
    <div class="fi-card p-1 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-1"
         data-aos="fade-up" data-aos-duration="500">
        @foreach([
            ['📋', 'SPMB', '#spmb'],
            ['📚', 'E-Learning', '#akademik'],
            ['📅', 'Jadwal', '#jadwal'],
            ['🏆', 'Prestasi', '#prestasi'],
            ['👥', 'Alumni', '#alumni'],
            ['📞', 'Kontak', '#kontak'],
        ] as [$icon, $label, $href])
            <a href="{{ $href }}"
               class="flex flex-col items-center gap-1.5 py-4 px-3 rounded-xl transition-colors hover:bg-amber-50 group"
               data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                <span class="text-2xl">{{ $icon }}</span>
                <span class="text-xs font-semibold group-hover:text-amber-700 transition-colors" style="color:var(--muted)">{{ $label }}</span>
            </a>
        @endforeach
    </div>
</section>
