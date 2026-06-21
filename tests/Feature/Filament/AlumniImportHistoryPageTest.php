<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\AlumniImportHistoryPage;
use App\Models\User;
use App\Services\AlumniImportHistory;
use App\Services\AlumniImportResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AlumniImportHistoryPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $user = User::factory()->create();
        Permission::findOrCreate('View:AlumniImportHistoryPage', 'web');
        $user->givePermissionTo('View:AlumniImportHistoryPage');
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->actingAs($user);
    }

    public function test_page_renders_with_volatility_warning(): void
    {
        Livewire::test(AlumniImportHistoryPage::class)
            ->assertSuccessful()
            ->assertSee('tidak disimpan di database')
            ->assertSee('Belum ada riwayat import');
    }

    public function test_page_shows_recorded_imported_and_failed_rows(): void
    {
        (new AlumniImportHistory)->record(
            new AlumniImportResult(
                created: 1,
                imported: [
                    ['row' => 2, 'action' => 'created', 'attributes' => ['full_name' => 'Andi Wijaya', 'certificate_number' => 'IJ-1']],
                ],
                failures: [
                    ['row' => 3, 'reason' => 'Kolom Nama Lengkap wajib diisi.', 'attributes' => ['full_name' => null]],
                ],
            ),
            'data-alumni.xlsx',
        );

        Livewire::test(AlumniImportHistoryPage::class)
            ->assertSuccessful()
            ->assertSee('data-alumni.xlsx')
            ->assertSee('Andi Wijaya')
            ->assertSee('Kolom Nama Lengkap wajib diisi.');
    }

    public function test_clear_action_removes_history(): void
    {
        $history = new AlumniImportHistory;
        $history->record(new AlumniImportResult(created: 1), 'data.xlsx');

        Livewire::test(AlumniImportHistoryPage::class)
            ->callAction('clear')
            ->assertNotified();

        $this->assertSame([], $history->all());
    }
}
