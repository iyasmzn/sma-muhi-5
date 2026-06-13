<?php

namespace Tests\Feature\Filament;

use App\Filament\Concerns\InteractsWithImagePicker;
use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Test harness exposing the protected static picker resolvers.
 */
class ImagePickerHarness
{
    use InteractsWithImagePicker;

    /** @param array<int, mixed>|null $blocks */
    public static function blocks(?array $blocks, ?string $baseName = null): array
    {
        return self::applyBlockImagePickers($blocks, $baseName);
    }

    public static function baseName(?string $title, string $feature): string
    {
        return self::imageBaseName($title, $feature);
    }

    /** @param array<string, mixed> $data */
    public static function gallery(array $data, ?string $baseName = null): array
    {
        return self::applyGalleryLibrary($data, baseName: $baseName);
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $keys
     */
    public static function flat(array $data, array $keys): array
    {
        return self::applyImagePickers($data, $keys);
    }
}

class ImagePickerTest extends TestCase
{
    use RefreshDatabase;

    public function test_flat_picker_resolves_library_selection_and_strips_helpers(): void
    {
        $media = Media::factory()->create(['path' => 'posts/images/cover.jpg']);

        $result = ImagePickerHarness::flat([
            'image' => null,
            'image_source' => 'library',
            'image_library' => $media->id,
            'image_name' => 'ignored',
            'image_alt' => 'ignored',
            'title' => 'Artikel',
        ], ['image']);

        $this->assertSame('posts/images/cover.jpg', $result['image']);
        $this->assertSame('Artikel', $result['title']);
        $this->assertArrayNotHasKey('image_source', $result);
        $this->assertArrayNotHasKey('image_library', $result);
        $this->assertArrayNotHasKey('image_name', $result);
        $this->assertArrayNotHasKey('image_alt', $result);
    }

    public function test_block_picker_resolves_cover_and_nested_images(): void
    {
        $cover = Media::factory()->create(['path' => 'pages/blocks/cover.jpg']);
        $slide = Media::factory()->create(['path' => 'pages/blocks/slide.jpg']);

        $blocks = [
            [
                'type' => 'image_cover',
                'image' => null,
                'image_source' => 'library',
                'image_library' => $cover->id,
                'caption' => 'Sampul',
            ],
            [
                'type' => 'image_carousel',
                'image_source' => 'upload', // stray helper from hidden cover picker
                'images' => [
                    [
                        'image' => null,
                        'image_source' => 'library',
                        'image_library' => $slide->id,
                        'caption' => 'Slide 1',
                    ],
                ],
            ],
        ];

        $result = ImagePickerHarness::blocks($blocks);

        $this->assertSame('pages/blocks/cover.jpg', $result[0]['image']);
        $this->assertArrayNotHasKey('image_source', $result[0]);
        $this->assertSame('Sampul', $result[0]['caption']);

        $this->assertArrayNotHasKey('image_source', $result[1]);
        $this->assertSame('pages/blocks/slide.jpg', $result[1]['images'][0]['image']);
        $this->assertArrayNotHasKey('image_library', $result[1]['images'][0]);
    }

    public function test_base_name_falls_back_to_feature_when_title_blank(): void
    {
        $this->assertSame('Berita Sekolah', ImagePickerHarness::baseName('  Berita Sekolah  ', 'Blog'));
        $this->assertSame('Blog', ImagePickerHarness::baseName(null, 'Blog'));
        $this->assertSame('Halaman', ImagePickerHarness::baseName('', 'Halaman'));
    }

    public function test_block_uploads_are_named_from_base_name_with_dedupe(): void
    {
        Storage::fake('public');
        $cover = UploadedFile::fake()->create('IMG_8821.jpg', 10, 'image/png')->store('posts/blocks', 'public');
        $slide = UploadedFile::fake()->create('IMG_9930.jpg', 10, 'image/png')->store('posts/blocks', 'public');

        $blocks = [
            ['type' => 'image_cover', 'image' => $cover, 'caption' => 'Sampul'],
            ['type' => 'image_carousel', 'images' => [
                ['image' => $slide, 'caption' => 'Slide'],
            ]],
        ];

        ImagePickerHarness::blocks($blocks, 'Berita Sekolah');

        // Names come from the title, not the random stored filename, deduped.
        $this->assertDatabaseHas('media', ['path' => $cover, 'name' => 'Berita Sekolah']);
        $this->assertDatabaseHas('media', ['path' => $slide, 'name' => 'Berita Sekolah-1']);
    }

    public function test_block_upload_does_not_rename_existing_media(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('x.jpg', 10, 'image/png')->store('posts/blocks', 'public');
        $existing = Media::factory()->create(['path' => $path, 'name' => 'Nama Lama']);

        ImagePickerHarness::blocks([
            ['type' => 'image_cover', 'image' => $path, 'caption' => ''],
        ], 'Judul Baru');

        $this->assertDatabaseHas('media', ['id' => $existing->id, 'name' => 'Nama Lama']);
        $this->assertSame(1, Media::where('path', $path)->count());
    }

    public function test_gallery_library_merges_into_gallery_and_strips_helper(): void
    {
        $a = Media::factory()->create(['path' => 'programs/gallery/a.jpg']);
        $b = Media::factory()->create(['path' => 'programs/gallery/b.jpg']);

        $result = ImagePickerHarness::gallery([
            'gallery' => ['programs/gallery/existing.jpg'],
            'gallery_library' => [$a->id, $b->id],
        ]);

        $this->assertSame(
            ['programs/gallery/existing.jpg', 'programs/gallery/a.jpg', 'programs/gallery/b.jpg'],
            $result['gallery'],
        );
        $this->assertArrayNotHasKey('gallery_library', $result);
    }

    public function test_gallery_uploads_are_synced_to_media_named_from_base(): void
    {
        Storage::fake('public');
        $a = UploadedFile::fake()->create('DSC01.jpg', 10, 'image/png')->store('programs/gallery', 'public');
        $b = UploadedFile::fake()->create('DSC02.jpg', 10, 'image/png')->store('programs/gallery', 'public');

        ImagePickerHarness::gallery([
            'gallery' => [$a, $b],
            'gallery_library' => [],
        ], 'Olimpiade Sains');

        $this->assertDatabaseHas('media', ['path' => $a, 'name' => 'Olimpiade Sains']);
        $this->assertDatabaseHas('media', ['path' => $b, 'name' => 'Olimpiade Sains-1']);
    }

    public function test_gallery_library_selection_is_not_renamed(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('lib.jpg', 10, 'image/png')->store('programs/gallery', 'public');
        $media = Media::factory()->create(['path' => $path, 'name' => 'Nama Asli']);

        $result = ImagePickerHarness::gallery([
            'gallery' => [],
            'gallery_library' => [$media->id],
        ], 'Program Baru');

        $this->assertContains($path, $result['gallery']);
        $this->assertDatabaseHas('media', ['id' => $media->id, 'name' => 'Nama Asli']);
        $this->assertSame(1, Media::where('path', $path)->count());
    }

    public function test_create_program_with_main_image_from_library(): void
    {
        $user = User::factory()->create();
        collect(['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'])
            ->each(fn (string $action) => $user->givePermissionTo(
                Permission::findOrCreate("{$action}:Program", 'web')
            ));
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->actingAs($user);

        $media = Media::factory()->create(['path' => 'programs/images/main.jpg']);

        Livewire::test(CreateProgram::class)
            ->set('data.title', 'Program Unggulan')
            ->set('data.slug', 'program-unggulan')
            ->set('data.description', 'Deskripsi program.')
            ->set('data.image_source', 'library')
            ->set('data.image_library', $media->id)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('programs', [
            'slug' => 'program-unggulan',
            'image' => 'programs/images/main.jpg',
        ]);

        $this->assertSame(1, Media::count());
    }
}
