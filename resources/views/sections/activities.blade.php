<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section id="kegiatan" class="mb-12 border-t pt-12" style="border-color:var(--border)">
        <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
            <div>
                <div class="fi-label mb-2">Ekstrakurikuler & Acara</div>
                <h2 class="text-2xl font-bold" style="color:var(--text)">Kegiatan Sekolah</h2>
            </div>
            <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 shrink-0">
                Lihat Semua <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['⚽', 'Sepak Bola',          'Ekskul',    'Latihan setiap Selasa & Jumat. Juara provinsi 3 tahun berturut-turut.',          'amber'],
                ['🎭', 'Seni & Drama',          'Ekskul',    'Pentas seni tahunan dan kompetisi teater tingkat nasional.',                     'purple'],
                ['🔬', 'Karya Ilmiah Remaja',   'Akademik',  'KIR aktif mengikuti lomba riset sains dan teknologi tingkat nasional.',          'blue'],
                ['🥋', 'Pencak Silat',          'Ekskul',    'Bela diri tradisional. Berprestasi di tingkat nasional dan internasional.',      'green'],
                ['💻', 'Coding & Robotika',     'Teknologi', 'Workshop coding, IoT, dan robotika untuk generasi digital Indonesia.',            'amber'],
                ['🎵', 'Paduan Suara',          'Seni',      'Koor terbaik tingkat kota. Tampil di berbagai acara nasional.',                  'purple'],
            ] as [$icon, $name, $tag, $desc, $color])
                @php
                    $tc = [
                        'amber'  => 'bg-amber-50 text-amber-700 border-amber-200',
                        'blue'   => 'bg-blue-50 text-blue-700 border-blue-200',
                        'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'green'  => 'bg-green-50 text-green-700 border-green-200',
                    ][$color];
                @endphp
                <div class="fi-card fi-card-hover p-5 flex gap-4"
                     data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                    <div class="text-3xl shrink-0">{{ $icon }}</div>
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="font-semibold text-sm" style="color:var(--text)">{{ $name }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md border {{ $tc }}">{{ $tag }}</span>
                        </div>
                        <p class="text-xs leading-relaxed" style="color:var(--muted)">{{ $desc }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
