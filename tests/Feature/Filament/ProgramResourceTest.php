<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\Programs\Pages\EditProgram;
use App\Filament\Resources\Programs\Pages\ListPrograms;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProgramResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    // ── List ──────────────────────────────────────────────────────

    public function test_list_page_can_render(): void
    {
        $programs = Program::factory()->count(3)->create();

        Livewire::test(ListPrograms::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($programs);
    }

    public function test_list_page_can_search(): void
    {
        $visible = Program::factory()->create(['title' => 'Tahfizh Quran']);
        $hidden = Program::factory()->create(['title' => 'Robotika']);

        Livewire::test(ListPrograms::class)
            ->searchTable('Tahfizh')
            ->assertCanSeeTableRecords([$visible])
            ->assertCanNotSeeTableRecords([$hidden]);
    }

    // ── Create ────────────────────────────────────────────────────

    public function test_create_page_can_render(): void
    {
        Livewire::test(CreateProgram::class)
            ->assertSuccessful();
    }

    public function test_can_create_program(): void
    {
        Livewire::test(CreateProgram::class)
            ->fillForm([
                'title' => 'Kelas Sains',
                'slug' => 'kelas-sains',
                'category' => 'Akademik',
                'icon' => '🔬',
                'excerpt' => 'Program pembinaan sains.',
                'description' => '<p>Deskripsi program sains.</p>',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 0,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Program::class, [
            'title' => 'Kelas Sains',
            'slug' => 'kelas-sains',
        ]);
    }

    public function test_create_validates_required_fields(): void
    {
        Livewire::test(CreateProgram::class)
            ->fillForm([
                'title' => null,
                'slug' => null,
                'description' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'title' => 'required',
                'slug' => 'required',
                'description' => 'required',
            ]);
    }

    // ── Featured limit ────────────────────────────────────────────

    public function test_cannot_feature_more_than_max_programs(): void
    {
        Program::factory()->count(Program::MAX_FEATURED)->featured()->create();

        Livewire::test(CreateProgram::class)
            ->fillForm([
                'title' => 'Program Ketujuh',
                'slug' => 'program-ketujuh',
                'description' => '<p>Deskripsi.</p>',
                'is_featured' => true,
            ])
            ->call('create')
            ->assertHasFormErrors(['is_featured']);

        $this->assertDatabaseMissing(Program::class, ['slug' => 'program-ketujuh']);
    }

    public function test_can_feature_up_to_max_programs(): void
    {
        Program::factory()->count(Program::MAX_FEATURED - 1)->featured()->create();

        Livewire::test(CreateProgram::class)
            ->fillForm([
                'title' => 'Program Keenam',
                'slug' => 'program-keenam',
                'description' => '<p>Deskripsi.</p>',
                'is_featured' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Program::class, [
            'slug' => 'program-keenam',
            'is_featured' => true,
        ]);
    }

    public function test_editing_a_featured_program_at_limit_does_not_trigger_limit_error(): void
    {
        Program::factory()->count(Program::MAX_FEATURED - 1)->featured()->create();
        $program = Program::factory()->featured()->create(['title' => 'Program Unggulan']);

        Livewire::test(EditProgram::class, ['record' => $program->id])
            ->fillForm(['title' => 'Program Unggulan Diperbarui'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Program::class, [
            'id' => $program->id,
            'title' => 'Program Unggulan Diperbarui',
        ]);
    }

    // ── Edit ──────────────────────────────────────────────────────

    public function test_edit_page_can_render(): void
    {
        $program = Program::factory()->create();

        Livewire::test(EditProgram::class, ['record' => $program->id])
            ->assertSuccessful();
    }

    public function test_can_edit_program(): void
    {
        $program = Program::factory()->create();

        Livewire::test(EditProgram::class, ['record' => $program->id])
            ->fillForm(['title' => 'Judul Baru'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Program::class, [
            'id' => $program->id,
            'title' => 'Judul Baru',
        ]);
    }

    // ── Model ─────────────────────────────────────────────────────

    public function test_factory_creates_valid_records(): void
    {
        $program = Program::factory()->create();

        $this->assertDatabaseHas(Program::class, ['id' => $program->id]);
    }

    public function test_published_scope_returns_only_published(): void
    {
        Program::factory()->count(2)->create(['is_published' => true]);
        Program::factory()->draft()->create();

        $this->assertCount(2, Program::published()->get());
    }

    public function test_featured_scope_returns_only_featured(): void
    {
        Program::factory()->count(2)->featured()->create();
        Program::factory()->create(['is_featured' => false]);

        $this->assertCount(2, Program::featured()->get());
    }

    public function test_thumbnail_url_returns_fallback_when_no_image(): void
    {
        $program = Program::factory()->create(['image' => null]);

        $this->assertStringContainsString('picsum.photos', $program->thumbnail_url);
    }

    public function test_gallery_urls_resolves_storage_paths(): void
    {
        $program = Program::factory()->withGallery(2)->create();

        $urls = $program->gallery_urls;

        $this->assertCount(2, $urls);
        $this->assertStringContainsString('/storage/programs/gallery/', $urls[0]);
    }

    public function test_gallery_urls_is_empty_when_no_gallery(): void
    {
        $program = Program::factory()->create(['gallery' => null]);

        $this->assertSame([], $program->gallery_urls);
    }
}
