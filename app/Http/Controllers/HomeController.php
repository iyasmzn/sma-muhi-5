<?php

namespace App\Http\Controllers;

use App\Models\ContactItem;
use App\Models\Post;
use App\Models\Slide;
use App\Models\Stat;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // 1 featured (first) + up to 6 in the grid
        $posts = Post::published()
            ->latest('published_at')
            ->limit(7)
            ->get();

        $stats = Stat::ordered()->get();
        $slides = Slide::active()->get();
        $contactItems = ContactItem::active()->get();

        $siteName = setting('site_name', config('app.name'));
        $siteTagline = setting('site_tagline', 'Unggul, Berkarakter, Berprestasi');

        $seo = [
            'title' => "{$siteName} — {$siteTagline}",
            'description' => setting('site_description', "Website resmi {$siteName}. Informasi SPMB, akademik, kegiatan, dan berita sekolah."),
            'canonical' => url('/'),
            'og_image' => setting('site_logo') ? asset('storage/'.setting('site_logo')) : null,
        ];

        return view('welcome', compact('posts', 'stats', 'slides', 'contactItems', 'seo'));
    }
}
