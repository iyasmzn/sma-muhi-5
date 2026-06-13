<?php

namespace Tests\Feature\Filament;

use App\Filament\Concerns\SyncsPhotoToMediaLibrary;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Test harness exposing the protected sync helper.
 */
class PhotoSyncHarness
{
    use SyncsPhotoToMediaLibrary;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function sync(array $data, string $field = 'photo', string $nameField = 'name'): array
    {
        return $this->syncPhotoToMediaLibrary($data, $field, $nameField);
    }
}

class SyncsPhotoToMediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_photo_is_registered_in_the_media_library(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('foto.jpg', 10, 'image/png')->store('teachers', 'public');

        (new PhotoSyncHarness)->sync(['photo' => $path, 'name' => 'Drs. Ahmad Fauzi']);

        // Named after the record, recorded but left unpublished from the gallery.
        $this->assertDatabaseHas('media', [
            'path' => $path,
            'name' => 'Drs. Ahmad Fauzi',
            'show_in_gallery' => false,
        ]);
    }

    public function test_array_photo_state_takes_the_first_path(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('foto.jpg', 10, 'image/png')->store('testimonials', 'public');

        (new PhotoSyncHarness)->sync(['photo' => [$path], 'name' => 'Budi Santoso']);

        $this->assertDatabaseHas('media', ['path' => $path, 'name' => 'Budi Santoso']);
    }

    public function test_existing_media_is_not_renamed(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('foto.jpg', 10, 'image/png')->store('teachers', 'public');
        $existing = Media::factory()->create(['path' => $path, 'name' => 'Nama Lama']);

        (new PhotoSyncHarness)->sync(['photo' => $path, 'name' => 'Nama Baru']);

        $this->assertDatabaseHas('media', ['id' => $existing->id, 'name' => 'Nama Lama']);
        $this->assertSame(1, Media::where('path', $path)->count());
    }

    public function test_blank_photo_creates_no_media(): void
    {
        (new PhotoSyncHarness)->sync(['photo' => null, 'name' => 'Tanpa Foto']);

        $this->assertSame(0, Media::count());
    }
}
