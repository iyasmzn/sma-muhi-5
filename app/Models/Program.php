<?php

namespace App\Models;

use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory;

    /**
     * Maximum number of featured programs shown on the landing page section.
     */
    public const MAX_FEATURED = 6;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'icon',
        'excerpt',
        'description',
        'image',
        'gallery',
        'is_featured',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'gallery' => 'array',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    // ── Computed Attributes ──────────────────────────────────

    /**
     * Thumbnail URL — falls back to picsum placeholder during development.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/'.$this->image)
            : "https://picsum.photos/seed/program-{$this->id}/800/500";
    }

    /**
     * Public URLs for every gallery image.
     *
     * @return array<int, string>
     */
    public function getGalleryUrlsAttribute(): array
    {
        return collect($this->gallery ?? [])
            ->filter()
            ->map(fn (string $path): string => asset('storage/'.$path))
            ->values()
            ->all();
    }

    /** Canonical URL for SEO. */
    public function getCanonicalUrlAttribute(): string
    {
        return route('programs.show', $this->slug);
    }

    /** 155-char meta description from excerpt or stripped description. */
    public function getMetaDescriptionAttribute(): string
    {
        $source = $this->excerpt ?: strip_tags($this->description);

        return Str::limit($source, 155);
    }
}
