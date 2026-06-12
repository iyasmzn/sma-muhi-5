<?php

namespace App\Filament\RichEditor;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\Plugins\Contracts\RichContentPlugin;
use Filament\Forms\Components\RichEditor\RichEditorTool;
use Filament\Support\Icons\Heroicon;
use Tiptap\Core\Extension;

/**
 * Adds a "fullscreen" toolbar button to the rich editor. Toggling it expands the
 * editor wrapper to fill the viewport so authors get a roomier writing surface.
 */
class FullscreenRichContentPlugin implements RichContentPlugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @return array<Extension>
     */
    public function getTipTapPhpExtensions(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    public function getTipTapJsExtensions(): array
    {
        return [];
    }

    /**
     * @return array<RichEditorTool>
     */
    public function getEditorTools(): array
    {
        return [
            RichEditorTool::make('fullscreen')
                ->label('Layar Penuh')
                ->icon(Heroicon::ArrowsPointingOut)
                ->activeStyling(false)
                ->jsHandler(<<<'JS'
                    (() => {
                        const wrapper = $el.closest('.fi-fo-rich-editor')

                        if (! wrapper) {
                            return
                        }

                        const isFullscreen = wrapper.classList.toggle('fi-fo-rich-editor-fullscreen')
                        document.body.classList.toggle('fi-rich-editor-fullscreen-active', isFullscreen)
                    })()
                JS),
        ];
    }

    /**
     * @return array<Action>
     */
    public function getEditorActions(): array
    {
        return [];
    }
}
