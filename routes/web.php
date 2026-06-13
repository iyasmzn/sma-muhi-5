<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SpmbController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

// Program Sekolah
Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{slug}', [ProgramController::class, 'show'])->name('programs.show');

// Galeri
Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery.index');

// Tenaga Pendidik
Route::get('/guru', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/guru/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

// Halaman Statis
Route::get('/page/{slug}', [StaticPageController::class, 'show'])->name('page.show');

// PPDB / SPMB
Route::get('/ppdb', [SpmbController::class, 'index'])->name('ppdb.index');
Route::post('/ppdb', [SpmbController::class, 'store'])->name('ppdb.store');

// Unduhan
Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/unduhan/{download}/download', [DownloadController::class, 'download'])->name('downloads.download');
