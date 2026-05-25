<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section id="sambutan" class="mb-12 border-t pt-12" style="border-color:var(--border)">
        <div class="fi-label mb-2" data-aos="fade-up">Sambutan</div>
        <h2 class="text-2xl font-bold mb-8" style="color:var(--text)" data-aos="fade-up" data-aos-delay="50">Sambutan Kepala Sekolah</h2>

        <div class="fi-card p-7 lg:p-10" data-aos="fade-up" data-aos-delay="100">
            <div class="grid lg:grid-cols-3 gap-8 items-start">
                {{-- Photo placeholder --}}
                <div class="flex flex-col items-center text-center" data-aos="fade-right" data-aos-delay="150">
                    <div class="w-32 h-32 rounded-2xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center text-5xl shadow-lg mb-4">👨‍💼</div>
                    <div class="font-bold text-sm" style="color:var(--text)">Drs. H. Ahmad Fauzi, M.Pd.</div>
                    <div class="text-xs mt-0.5" style="color:var(--muted)">Kepala Sekolah</div>
                    <div class="mt-3 fi-badge">NIP. 197003012005011001</div>
                </div>
                {{-- Message --}}
                <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="150">
                    <svg class="w-8 h-8 text-amber-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-sm leading-relaxed mb-4" style="color:var(--muted)">
                        Assalamu'alaikum Warahmatullahi Wabarakatuh. Puji syukur kepada Allah SWT atas segala nikmat dan karunia-Nya sehingga {{ setting('site_name', config('app.name')) }} terus berkembang menjadi lembaga pendidikan yang unggul dan terpercaya.
                    </p>
                    <p class="text-sm leading-relaxed mb-4" style="color:var(--muted)">
                        Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi yang tidak hanya mencerdaskan akal, tetapi juga membentuk karakter mulia. Dengan dukungan tenaga pendidik profesional dan fasilitas modern, kami yakin setiap siswa dapat meraih potensi terbaiknya.
                    </p>
                    <p class="text-sm leading-relaxed" style="color:var(--muted)">
                        Selamat datang dan bergabunglah bersama keluarga besar {{ setting('site_name', config('app.name')) }}. Mari bersama-sama kita wujudkan generasi penerus bangsa yang cerdas, berkarakter, dan berakhlak mulia.
                    </p>
                    <div class="mt-6 pt-5 border-t flex items-center gap-3" style="border-color:var(--border)">
                        <div class="text-xs" style="color:var(--muted)">Wassalamu'alaikum Wr. Wb.</div>
                        <div class="ml-auto text-xs font-semibold text-amber-600">Drs. H. Ahmad Fauzi, M.Pd.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
