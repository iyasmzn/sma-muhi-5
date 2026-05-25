<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaLibraryService
{
    /**
     * Ensure the given file path exists as a Media record.
     * Safe to call multiple times — uses firstOrCreate to avoid duplicates.
     */
    public function sync(string $path, string $disk = 'public'): void
    {
        if (blank($path)) {
            return;
        }

        try {
            $storage = Storage::disk($disk);

            if (! $storage->exists($path)) {
                return;
            }

            Media::firstOrCreate(
                ['path' => $path, 'disk' => $disk],
                [
                    'name' => pathinfo(basename($path), PATHINFO_FILENAME),
                    'mime_type' => $storage->mimeType($path),
                    'size' => $storage->size($path),
                    'uploaded_by' => Auth::id(),
                ]
            );
        } catch (\Throwable) {
            // Never block a save because of media sync failure.
        }
    }
}
