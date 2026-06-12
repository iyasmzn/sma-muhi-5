<?php

namespace App\Models;

use App\Services\EmbedVideo;
use Database\Factories\MediaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;

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
    ];

    // ── Accessors ─────────────────────────────────────────────────────

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

    // ── Relationships ─────────────────────────────────────────────────

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
