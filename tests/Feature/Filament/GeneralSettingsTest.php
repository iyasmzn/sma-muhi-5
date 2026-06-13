<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\GeneralSettings;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GeneralSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_selecting_logo_from_library_stores_media_path(): void
    {
        $logo = Media::factory()->create([
            'name' => 'Logo Lama',
            'path' => 'settings/logo-lama.png',
        ]);

        Livewire::test(GeneralSettings::class)
            ->set('data.site_name', 'SMA Test')
            ->set('data.site_logo_source', 'library')
            ->set('data.site_logo_library', $logo->id)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', [
            'key' => 'site_logo',
            'value' => 'settings/logo-lama.png',
        ]);

        // No new media is created when picking from the library.
        $this->assertSame(1, Media::count());
    }

    public function test_helper_fields_are_not_persisted_as_settings(): void
    {
        $logo = Media::factory()->create(['path' => 'settings/logo.png']);

        Livewire::test(GeneralSettings::class)
            ->set('data.site_name', 'SMA Test')
            ->set('data.site_logo_source', 'library')
            ->set('data.site_logo_library', $logo->id)
            ->call('save')
            ->assertHasNoFormErrors();

        foreach (['site_logo_source', 'site_logo_library', 'site_logo_name', 'site_logo_alt'] as $helper) {
            $this->assertDatabaseMissing('settings', ['key' => $helper]);
        }
    }
}
