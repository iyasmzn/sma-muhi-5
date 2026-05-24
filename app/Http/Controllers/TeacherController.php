<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    private const PER_PAGE = 12;

    private const SORT_OPTIONS = [
        'default' => ['sort_order', 'asc'],
        'name_asc' => ['name', 'asc'],
        'name_desc' => ['name', 'desc'],
        'position' => ['position', 'asc'],
    ];

    public function index(Request $request): View
    {
        $search = $request->query('search', '');
        $position = $request->query('position', '');
        $sort = $request->query('sort', 'default');

        [$sortColumn, $sortDirection] = self::SORT_OPTIONS[$sort] ?? self::SORT_OPTIONS['default'];

        $teachers = Teacher::active()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($position, fn ($q) => $q->where('position', $position))
            ->orderBy($sortColumn, $sortDirection)
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $positions = Teacher::where('is_active', true)
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => "Tenaga Pendidik | {$siteName}",
            'description' => "Kenali tenaga pendidik profesional {$siteName}. Guru-guru berpengalaman dan berdedikasi untuk pendidikan berkualitas.",
            'canonical' => route('teachers.index'),
        ];

        return view('teachers.index', compact('teachers', 'positions', 'search', 'position', 'sort', 'seo'));
    }

    public function show(Teacher $teacher): View
    {
        abort_unless($teacher->is_active, 404);

        $siteName = setting('site_name', config('app.name'));

        $seo = [
            'title' => "{$teacher->name} — Tenaga Pendidik | {$siteName}",
            'description' => "{$teacher->name} adalah {$teacher->position} di {$siteName}"
                .($teacher->subject ? ", mengajar {$teacher->subject}." : '.'),
            'canonical' => route('teachers.show', $teacher),
            'og_image' => $teacher->photo_url,
        ];

        return view('teachers.show', compact('teacher', 'seo'));
    }
}
