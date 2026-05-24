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

        return view('welcome', compact('posts', 'stats', 'slides', 'contactItems'));
    }
}
