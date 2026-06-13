<?php

namespace App\Models;

use App\Services\EmbedVideo;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Media extends Model
{
    /** @use HasFactory<MediaFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'alt',
        'description',
        'path',
        'embed_provider',
        'embed_url',
        'disk',
        'mime_type',
        'size',
        'uploaded_by',
        'show_in_gallery',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'show_in_gallery' => 'boolean',
        ];
    }

    // ── Scopes ────────────────────────────────────────────────────────

    /**
     * Visual media (images & video embeds) flagged to appear in the public
     * gallery, newest first.
     */
    public function scopeInGallery(Builder $query): Builder
    {
        return $query
            ->where('show_in_gallery', true)
            ->where(function (Builder $q): void {
                $q->where('mime_type', 'like', 'image/%')
                    ->orWhereNotNull('embed_provider');
            })
            ->latest();
    }

    // ── Accessors ─────────────────────────────────────────────────────

    /**
     * Image URL used to preview the item in the gallery — the embed thumbnail
     * for video embeds, otherwise the stored image URL.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->is_embed ? $this->embed_thumbnail : $this->url;
    }

    /**
     * Public URL to access the item — the embed URL for embeds, otherwise the
     * stored file URL.
     */
    public function getUrlAttribute(): string
    {
        if ($this->is_embed) {
            return $this->embed_url ?? '';
        }

        return asset('storage/'.$this->path);
    }

    /**
     * Whether this item is an external video embed rather than an uploaded file.
     */
    public function getIsEmbedAttribute(): bool
    {
        return filled($this->embed_provider);
    }

    /**
     * Thumbnail/preview image URL for the item.
     */
    public function getEmbedThumbnailAttribute(): ?string
    {
        if (! $this->is_embed) {
            return null;
        }

        return EmbedVideo::thumbnail($this->embed_provider, $this->embed_url ?? '');
    }

    /**
     * Ready-to-use responsive iframe for rendering the embed on the frontend.
     */
    public function getEmbedHtmlAttribute(): ?HtmlString
    {
        if (! $this->is_embed) {
            return null;
        }

        return EmbedVideo::iframeHtml($this->embed_provider, $this->embed_url ?? '', $this->name);
    }

    /**
     * Human-readable file size (e.g. "1.4 MB").
     */
    public function getSizeFormattedAttribute(): string
    {
        $bytes = (int) $this->size;

        return match (true) {
            $bytes >= 1_048_576 => round($bytes / 1_048_576, 1).' MB',
            $bytes >= 1_024 => round($bytes / 1_024, 1).' KB',
            default => $bytes.' B',
        };
    }

    /**
     * Whether the file is an image.
     */
    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Short type label for the item (Image, PDF, YouTube, etc.).
     */
    public function getTypeLabel(): string
    {
        if ($this->is_embed) {
            return EmbedVideo::label($this->embed_provider);
        }

        return match (true) {
            str_starts_with($this->mime_type ?? '', 'image/') => 'Gambar',
            $this->mime_type === 'application/pdf' => 'PDF',
            str_starts_with($this->mime_type ?? '', 'video/') => 'Video',
            default => 'File',
        };
    }

    /**
     * Where the media originates from — the embed provider for video embeds,
     * otherwise a human label derived from the storage folder.
     */
    public function getOriginLabel(): string
    {
        if ($this->is_embed) {
            return EmbedVideo::label($this->embed_provider);
        }

        $segment = explode('/', (string) $this->path)[0];

        return match ($segment) {
            'media' => 'Galeri',
            'settings' => 'Pengaturan',
            'posts' => 'Blog',
            'pages' => 'Halaman',
            'slides' => 'Slide',
            'principal' => 'Kepala Sekolah',
            'programs' => 'Program',
            'teachers' => 'Guru',
            'testimonials' => 'Testimoni',
            'downloads' => 'Unduhan',
            'alumni-imports' => 'Alumni',
            default => $segment !== '' ? Str::headline($segment) : 'Lainnya',
        };
    }

    // ── Relationships ─────────────────────────────────────────────────

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
