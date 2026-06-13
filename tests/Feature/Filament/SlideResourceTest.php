<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Slides\Pages\CreateSlide;
use App\Filament\Resources\Slides\Pages\EditSlide;
use App\Models\Media;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SlideResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        collect(['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'])
            ->each(fn (string $action) => $user->givePermissionTo(
                Permission::findOrCreate("{$action}:Slide", 'web')
            ));

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->actingAs($user);
    }

    public function test_create_slide_with_image_from_library(): void
    {
        $media = Media::factory()->create(['path' => 'slides/bg.jpg']);

        Livewire::test(CreateSlide::class)
            ->set('data.title', 'Slide Utama')
            ->set('data.sort_order', 0)
            ->set('data.image_source', 'library')
            ->set('data.image_library', $media->id)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('slides', [
            'title' => 'Slide Utama',
            'image' => 'slides/bg.jpg',
        ]);

        // Picking from the library must not create a duplicate media record.
        $this->assertSame(1, Media::count());
    }

    public function test_edit_slide_switches_image_to_library_item(): void
    {
        $slide = Slide::factory()->create(['image' => 'slides/old.jpg']);
        $media = Media::factory()->create(['path' => 'slides/new.jpg']);

        Livewire::test(EditSlide::class, ['record' => $slide->id])
            ->set('data.image_source', 'library')
            ->set('data.image_library', $media->id)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('slides', [
            'id' => $slide->id,
            'image' => 'slides/new.jpg',
        ]);
    }

    public function test_picker_helper_fields_are_not_stored_on_the_model(): void
    {
        $media = Media::factory()->create(['path' => 'slides/bg.jpg']);

        Livewire::test(CreateSlide::class)
            ->set('data.title', 'Slide Helper')
            ->set('data.sort_order', 0)
            ->set('data.image_source', 'library')
            ->set('data.image_library', $media->id)
            ->call('create')
            ->assertHasNoFormErrors();

        $slide = Slide::where('title', 'Slide Helper')->firstOrFail();

        $this->assertArrayNotHasKey('image_source', $slide->getAttributes());
        $this->assertArrayNotHasKey('image_library', $slide->getAttributes());
    }
}
