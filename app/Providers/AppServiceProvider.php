<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Slide;
use App\Models\Teacher;
use App\Observers\PostObserver;
use App\Observers\SlideObserver;
use App\Observers\TeacherObserver;
use Filament\Tables\Columns\ImageColumn;
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

        // ── Auto-sync uploads to Media Library ───────────────────────────────────
        Slide::observe(SlideObserver::class);
        Teacher::observe(TeacherObserver::class);
        Post::observe(PostObserver::class);
    }
}
