<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $programs = Program::published()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->ordered()
            ->get();

        $categories = Program::published()
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $appName = config('app.name');

        $seo = [
            'title' => $category
                ? "{$category} — Program Sekolah | {$appName}"
                : "Program Sekolah | {$appName}",
            'description' => $category
                ? "Daftar program {$category} di {$appName}."
                : "Jelajahi program unggulan, ekstrakurikuler, dan kegiatan pembelajaran di {$appName}.",
            'canonical' => route('programs.index', $category ? ['category' => $category] : []),
        ];

        return view('programs.index', compact('programs', 'categories', 'category', 'seo'));
    }

    public function show(string $slug): View
    {
        $program = Program::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Program::published()
            ->where('id', '!=', $program->id)
            ->when($program->category, fn ($q) => $q->where('category', $program->category))
            ->ordered()
            ->limit(3)
            ->get();

        $seo = [
            'title' => "{$program->title} | ".config('app.name'),
            'description' => $program->meta_description,
            'canonical' => $program->canonical_url,
            'og_image' => $program->thumbnail_url,
        ];

        return view('programs.show', compact('program', 'related', 'seo'));
    }
}
