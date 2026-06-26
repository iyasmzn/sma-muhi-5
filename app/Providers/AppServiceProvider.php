<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Slide;
use App\Models\Teacher;
use App\Observers\PostObserver;
use App\Observers\SlideObserver;
use App\Observers\TeacherObserver;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Prevent ImageColumn from checking file existence on every table load ──
        ImageColumn::configureUsing(function (ImageColumn $column): void {
            $column->checkFileExistence(false);
        });

        // ── Auto-sync uploads to Galeri & Kegiatan Sekolah ───────────────────────────────────
        Slide::observe(SlideObserver::class);
        Teacher::observe(TeacherObserver::class);
        Post::observe(PostObserver::class);

        // ── Chart.js plugin: tampilkan angka di dalam slice pie ──────────────────────────────
        // Skip during console (e.g. `package:discover` on deploy) because Vite::asset()
        // eagerly reads the manifest, which does not exist yet before `npm run build`.
        if (! $this->app->runningInConsole()) {
            FilamentAsset::register([
                Js::make('chart-js-plugins', Vite::asset('resources/js/filament-chart-js-plugins.js'))->module(),
            ]);
        }

        // Force HTTPS only when the application is running in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
