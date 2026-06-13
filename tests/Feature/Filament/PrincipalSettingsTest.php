<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\PrincipalSettings;
use App\Models\Media;
use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrincipalSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_selecting_photo_from_library_stores_media_path(): void
    {
        $photo = Media::factory()->create([
            'name' => 'Foto Kepsek',
            'path' => 'principal/kepsek.jpg',
        ]);

        Livewire::test(PrincipalSettings::class)
            ->set('data.principal_name', 'Drs. Ahmad Fauzi')
            ->set('data.principal_photo_source', 'library')
            ->set('data.principal_photo_library', $photo->id)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'key' => 'principal_photo',
            'value' => 'principal/kepsek.jpg',
        ]);

        $this->assertSame(1, Media::count());
    }

    public function test_picker_helper_fields_are_not_persisted_as_settings(): void
    {
        $photo = Media::factory()->create(['path' => 'principal/kepsek.jpg']);

        Livewire::test(PrincipalSettings::class)
            ->set('data.principal_name', 'Drs. Ahmad Fauzi')
            ->set('data.principal_photo_source', 'library')
            ->set('data.principal_photo_library', $photo->id)
            ->call('save')
            ->assertHasNoFormErrors();

        foreach (['principal_photo_source', 'principal_photo_library', 'principal_photo_name', 'principal_photo_alt'] as $helper) {
            $this->assertDatabaseMissing('settings', ['key' => $helper]);
        }
    }

    public function test_saves_when_default_sambutan_page_does_not_exist(): void
    {
        // No StaticPage exists — the default slug must not break validation.
        Livewire::test(PrincipalSettings::class)
            ->assertSet('data.principal_page', null)
            ->set('data.principal_name', 'Drs. Ahmad Fauzi')
            ->call('save')
            ->assertHasNoFormErrors();
    }

    public function test_keeps_selected_static_page_when_active(): void
    {
        $page = StaticPage::factory()->create([
            'slug' => 'sambutan-kepala-sekolah',
            'is_active' => true,
        ]);

        Livewire::test(PrincipalSettings::class)
            ->assertSet('data.principal_page', $page->slug)
            ->set('data.principal_name', 'Drs. Ahmad Fauzi')
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'key' => 'principal_page',
            'value' => 'sambutan-kepala-sekolah',
        ]);
    }
}
