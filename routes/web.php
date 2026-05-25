<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

// Tenaga Pendidik
Route::get('/guru', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/guru/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

// Unduhan
Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/unduhan/{download}/download', [DownloadController::class, 'download'])->name('downloads.download');
