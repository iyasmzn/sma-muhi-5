<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Alumnis\Pages\CreateAlumni;
use App\Filament\Resources\Alumnis\Pages\EditAlumni;
use App\Filament\Resources\Alumnis\Pages\ListAlumnis;
use App\Models\Alumni;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AlumniResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->grantAlumniPermissions($this->user);

        $this->actingAs($this->user);
    }

    /**
     * Grant the Shield permissions the AlumniResource pages require. In
     * production these are seeded and synced onto the super_admin role.
     */
    private function grantAlumniPermissions(User $user): void
    {
        $permissions = collect(['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'])
            ->map(fn (string $action): Permission => Permission::findOrCreate("{$action}:Alumni", 'web'));

        $user->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    // ── List ──────────────────────────────────────────────────────

    public function test_list_page_can_render(): void
    {
        $alumni = Alumni::factory()->count(3)->create();

        Livewire::test(ListAlumnis::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords($alumni);
    }

    public function test_list_page_can_search_by_name(): void
    {
        $visible = Alumni::factory()->create(['full_name' => 'Habibie Rahman']);
        $hidden = Alumni::factory()->create(['full_name' => 'Sukarno Putra']);

        Livewire::test(ListAlumnis::class)
            ->searchTable('Habibie')
            ->assertCanSeeTableRecords([$visible])
            ->assertCanNotSeeTableRecords([$hidden]);
    }

    public function test_list_can_filter_by_entered_ptn(): void
    {
        $inPtn = Alumni::factory()->enteredPtn()->create();
        $notInPtn = Alumni::factory()->notEnteredPtn()->create();

        Livewire::test(ListAlumnis::class)
            ->filterTable('entered_ptn', true)
            ->assertCanSeeTableRecords([$inPtn])
            ->assertCanNotSeeTableRecords([$notInPtn]);
    }

    // ── Create ────────────────────────────────────────────────────

    public function test_create_page_can_render(): void
    {
        Livewire::test(CreateAlumni::class)->assertSuccessful();
    }

    public function test_can_create_alumni(): void
    {
        Livewire::test(CreateAlumni::class)
            ->fillForm([
                'full_name' => 'Rina Amelia',
                'nickname' => 'Rina',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '2003-04-10',
                'major' => 'IPA',
                'graduation_year' => 2021,
                'certificate_number' => 'IJ-555',
                'entered_ptn' => true,
                'ptn_name' => 'UGM',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Alumni::class, [
            'full_name' => 'Rina Amelia',
            'certificate_number' => 'IJ-555',
            'entered_ptn' => true,
            'ptn_name' => 'UGM',
        ]);
    }

    public function test_create_requires_full_name(): void
    {
        Livewire::test(CreateAlumni::class)
            ->fillForm(['full_name' => null])
            ->call('create')
            ->assertHasFormErrors(['full_name' => 'required']);
    }

    public function test_ptn_name_is_required_when_entered_ptn_is_true(): void
    {
        Livewire::test(CreateAlumni::class)
            ->fillForm([
                'full_name' => 'Doni Saputra',
                'entered_ptn' => true,
                'ptn_name' => null,
            ])
            ->call('create')
            ->assertHasFormErrors(['ptn_name' => 'required']);
    }

    public function test_ptn_name_is_not_required_when_not_entered_ptn(): void
    {
        Livewire::test(CreateAlumni::class)
            ->fillForm([
                'full_name' => 'Doni Saputra',
                'entered_ptn' => false,
                'ptn_name' => null,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Alumni::class, [
            'full_name' => 'Doni Saputra',
            'entered_ptn' => false,
        ]);
    }

    public function test_certificate_number_must_be_unique(): void
    {
        Alumni::factory()->create(['certificate_number' => 'IJ-DUP']);

        Livewire::test(CreateAlumni::class)
            ->fillForm([
                'full_name' => 'Eka Pratama',
                'certificate_number' => 'IJ-DUP',
            ])
            ->call('create')
            ->assertHasFormErrors(['certificate_number' => 'unique']);
    }

    // ── Edit ──────────────────────────────────────────────────────

    public function test_can_edit_alumni(): void
    {
        $alumni = Alumni::factory()->create(['full_name' => 'Nama Awal']);

        Livewire::test(EditAlumni::class, ['record' => $alumni->id])
            ->fillForm(['full_name' => 'Nama Diperbarui'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Alumni::class, [
            'id' => $alumni->id,
            'full_name' => 'Nama Diperbarui',
        ]);
    }

    // ── Model ─────────────────────────────────────────────────────

    public function test_entered_ptn_scope_returns_only_ptn_alumni(): void
    {
        Alumni::factory()->count(2)->enteredPtn()->create();
        Alumni::factory()->notEnteredPtn()->create();

        $this->assertCount(2, Alumni::query()->enteredPtn()->get());
    }
}
