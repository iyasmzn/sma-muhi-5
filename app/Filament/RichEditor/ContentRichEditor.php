<?php

namespace App\Filament\RichEditor;

use Filament\Forms\Components\RichEditor;

/**
 * Shared rich editor used for long-form content (posts, programs, static pages).
 * Ships a fuller toolbar plus a fullscreen toggle for a roomier writing surface.
 */
class ContentRichEditor extends RichEditor
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                ['h2', 'h3'],
                ['alignStart', 'alignCenter', 'alignEnd'],
                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                ['table', 'attachFiles'],
                ['undo', 'redo'],
                ['fullscreen'],
            ])
            ->plugins([
                FullscreenRichContentPlugin::make(),
            ]);
    }
}
