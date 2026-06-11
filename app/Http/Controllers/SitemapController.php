<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Program;
use App\Models\StaticPage;
use App\Models\Teacher;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /dashboard',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /filament',
            'Disallow: /livewire',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function index(): Response
    {
        $posts = Post::published()
            ->select('slug', 'updated_at', 'published_at')
            ->latest('published_at')
            ->get();

        $teachers = Teacher::active()
            ->select('id', 'updated_at')
            ->get();

        $staticPages = StaticPage::active()
            ->select('slug', 'updated_at')
            ->ordered()
            ->get();

        $programs = Program::published()
            ->select('slug', 'updated_at')
            ->ordered()
            ->get();

        $content = view('sitemap.index', compact('posts', 'teachers', 'staticPages', 'programs'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}
