<?php

namespace App\Filament\Concerns;

use App\Models\Media;
use App\Services\MediaLibraryService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Reusable image field for settings pages and resources: choose an existing
 * item from the media library (with thumbnail options & preview) or upload a
 * new one. On upload, the name & alt fields auto-fill from the original file
 * name and stay editable; the name is made unique within the library.
 *
 * Backing form keys per field `$key`:
 *  - `$key`            — the stored file path
 *  - `{$key}_source`   — 'upload' | 'library'
 *  - `{$key}_library`  — selected Media id (library mode)
 *  - `{$key}_name`     — Media name (upload mode)
 *  - `{$key}_alt`      — Media alt text (upload mode)
 *
 * Methods are static so they can be used from both Livewire pages and static
 * resource form classes (e.g. `SlideForm::configure()`).
 */
trait InteractsWithImagePicker
{
    /**
     * @param  list<string>  $accepted
     */
    protected static function imagePicker(
        string $key,
        string $label,
        string $hint,
        array $accepted,
        int $width,
        int $height,
        string $directory = 'settings',
        ?string $aspectRatio = null,
    ): Fieldset {
        $isUpload = fn (Get $get): bool => ($get("{$key}_source") ?? 'upload') === 'upload';
        $isLibrary = fn (Get $get): bool => $get("{$key}_source") === 'library';

        $upload = FileUpload::make($key)
            ->label('Unggah Gambar')
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->acceptedFileTypes($accepted)
            ->automaticallyResizeImagesToWidth((string) $width)
            ->automaticallyResizeImagesToHeight((string) $height)
            ->when($aspectRatio !== null, fn (FileUpload $component): FileUpload => $component
                ->automaticallyCropImagesToAspectRatio($aspectRatio)
                ->automaticallyResizeImagesMode('cover'))
            ->live()
            ->afterStateUpdated(function ($state, Set $set) use ($key): void {
                $file = collect(is_array($state) ? $state : [$state])->first();

                if (! is_object($file) || ! method_exists($file, 'getClientOriginalName')) {
                    return;
                }

                $base = Str::of(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    ->replace(['-', '_'], ' ')
                    ->squish()
                    ->title()
                    ->toString();

                $set("{$key}_name", app(MediaLibraryService::class)->uniqueName($base));
                $set("{$key}_alt", $base);
            })
            ->visible($isUpload)
            ->hint($hint)
            ->columnSpanFull();

        return Fieldset::make($label)
            ->schema([
                ToggleButtons::make("{$key}_source")
                    ->label('Sumber Gambar')
                    ->options([
                        'upload' => 'Upload Baru',
                        'library' => 'Pilih dari Media',
                    ])
                    ->icons([
                        'upload' => 'heroicon-o-arrow-up-tray',
                        'library' => 'heroicon-o-photo',
                    ])
                    ->default('upload')
                    ->inline()
                    ->live()
                    ->columnSpanFull(),

                Select::make("{$key}_library")
                    ->label('Pilih dari Media')
                    ->allowHtml()
                    ->options(fn (): array => static::mediaLibraryOptions())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live()
                    ->placeholder('Cari nama media…')
                    ->visible($isLibrary)
                    ->required($isLibrary)
                    ->columnSpanFull(),

                Placeholder::make("{$key}_preview")
                    ->label('Pratinjau')
                    ->visible(fn (Get $get): bool => $isLibrary($get) && filled($get("{$key}_library")))
                    ->content(function (Get $get) use ($key): ?HtmlString {
                        $media = Media::find($get("{$key}_library"));

                        if (! $media) {
                            return null;
                        }

                        return new HtmlString(
                            '<img src="'.e($media->thumbnail_url).'" alt="'.e($media->alt ?? $media->name).'" '
                            .'style="max-height:160px;max-width:100%;border-radius:0.75rem;border:1px solid rgba(0,0,0,.1);object-fit:contain;" />'
                        );
                    })
                    ->columnSpanFull(),

                $upload,

                TextInput::make("{$key}_name")
                    ->label('Nama Media')
                    ->maxLength(200)
                    ->helperText('Terisi otomatis dari nama berkas; ditambah angka bila sudah dipakai.')
                    ->visible($isUpload),

                TextInput::make("{$key}_alt")
                    ->label('Alt Text')
                    ->maxLength(500)
                    ->helperText('Deskripsi singkat gambar untuk SEO & aksesibilitas.')
                    ->visible($isUpload),
            ]);
    }

    /**
     * Default state for the picker's source toggles, to merge into form fill.
     *
     * @param  list<string>  $keys
     * @return array<string, string>
     */
    protected static function imagePickerDefaults(array $keys): array
    {
        return collect($keys)
            ->mapWithKeys(fn (string $key): array => ["{$key}_source" => 'upload'])
            ->all();
    }

    /**
     * Resolve each image field from its chosen source, persisting uploads to the
     * media library. The picker's helper fields are not dehydrated, so only the
     * resolved path is returned in the data.
     *
     * @param  array<string, mixed>  $data
     * @param  list<string>  $keys
     * @return array<string, mixed>
     */
    protected static function applyImagePickers(array $data, array $keys): array
    {
        $media = app(MediaLibraryService::class);

        foreach ($keys as $key) {
            if (($data["{$key}_source"] ?? 'upload') === 'library') {
                $selected = Media::find($data["{$key}_library"] ?? null);
                $data[$key] = $selected?->path ?? ($data[$key] ?? null);
            } elseif (! blank($data[$key] ?? null)) {
                $media->store(
                    $data[$key],
                    $data["{$key}_name"] ?? null,
                    $data["{$key}_alt"] ?? null,
                );
            }

            unset(
                $data["{$key}_source"],
                $data["{$key}_library"],
                $data["{$key}_name"],
                $data["{$key}_alt"],
            );
        }

        return $data;
    }

    /**
     * Image media options rendered with a thumbnail beside the name.
     *
     * @return array<int, string>
     */
    protected static function mediaLibraryOptions(): array
    {
        return Media::query()
            ->where('mime_type', 'like', 'image/%')
            ->orderByDesc('id')
            ->get()
            ->mapWithKeys(fn (Media $media): array => [
                $media->id => '<div style="display:flex;align-items:center;gap:.5rem;">'
                    .'<img src="'.e($media->thumbnail_url).'" alt="" style="width:2rem;height:2rem;border-radius:.375rem;object-fit:cover;flex-shrink:0;" />'
                    .'<span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'.e($media->name).'</span>'
                    .'</div>',
            ])
            ->all();
    }
}
