<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type'); // null | 'foto' | 'video'

        $items = Media::inGallery()
            ->when($type === 'foto', fn (Builder $q) => $q->whereNull('embed_provider'))
            ->when($type === 'video', fn (Builder $q) => $q->whereNotNull('embed_provider'))
            ->paginate(24)
            ->withQueryString();

        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => 'Galeri Sekolah | '.$siteName,
            'description' => "Dokumentasi foto kegiatan, fasilitas, prestasi, dan momen berharga di {$siteName}.",
            'canonical' => route('gallery.index', $type ? ['type' => $type] : []),
        ];

        return view('gallery.index', compact('items', 'type', 'seo'));
    }
}
