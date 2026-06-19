<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Media;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class MediaResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->grantMediaPermissions($this->user);

        $this->actingAs($this->user);
    }

    /**
     * Grant the Shield permissions the MediaResource pages require. In
     * production these are seeded and synced onto the super_admin role.
     */
    private function grantMediaPermissions(User $user): void
    {
        $permissions = collect(['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny'])
            ->map(fn (string $action): Permission => Permission::findOrCreate("{$action}:Media", 'web'));

        $user->givePermissionTo($permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    // ── List & table ──────────────────────────────────────────────

    public function test_list_page_renders_files_and_embeds(): void
    {
        $file = Media::factory()->create();
        $embed = Media::factory()->embed()->create();

        Livewire::test(ListMedia::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords([$file, $embed]);
    }

    public function test_type_filter_returns_only_embeds(): void
    {
        $embed = Media::factory()->embed()->create();
        $file = Media::factory()->create();

        Livewire::test(ListMedia::class)
            ->filterTable('type', 'embed')
            ->assertCanSeeTableRecords([$embed])
            ->assertCanNotSeeTableRecords([$file]);
    }

    public function test_origin_filter_returns_only_media_from_that_folder(): void
    {
        $teacher = Media::factory()->create(['path' => 'teachers/budi.jpg']);
        $slide = Media::factory()->create(['path' => 'slides/banner.jpg']);

        Livewire::test(ListMedia::class)
            ->filterTable('origin', 'teachers')
            ->assertCanSeeTableRecords([$teacher])
            ->assertCanNotSeeTableRecords([$slide]);
    }

    public function test_origin_filter_can_select_video_embeds(): void
    {
        $embed = Media::factory()->embed()->create();
        $file = Media::factory()->create(['path' => 'teachers/budi.jpg']);

        Livewire::test(ListMedia::class)
            ->filterTable('origin', 'embed')
            ->assertCanSeeTableRecords([$embed])
            ->assertCanNotSeeTableRecords([$file]);
    }

    public function test_origin_options_list_only_present_origins_with_labels(): void
    {
        Media::factory()->create(['path' => 'teachers/budi.jpg']);
        Media::factory()->create(['path' => 'settings/logo.png']);
        Media::factory()->embed()->create();

        $options = Media::originOptions();

        $this->assertSame('Guru', $options['teachers']);
        $this->assertSame('Pengaturan', $options['settings']);
        $this->assertSame('Video Embed', $options['embed']);
        // Folders with no media are not offered.
        $this->assertArrayNotHasKey('programs', $options);
    }

    public function test_publication_filter_returns_only_published_media(): void
    {
        $published = Media::factory()->inGallery()->create();
        $hidden = Media::factory()->create();

        Livewire::test(ListMedia::class)
            ->filterTable('show_in_gallery', true)
            ->assertCanSeeTableRecords([$published])
            ->assertCanNotSeeTableRecords([$hidden]);
    }

    public function test_publication_filter_returns_only_hidden_media(): void
    {
        $published = Media::factory()->inGallery()->create();
        $hidden = Media::factory()->create();

        Livewire::test(ListMedia::class)
            ->filterTable('show_in_gallery', false)
            ->assertCanSeeTableRecords([$hidden])
            ->assertCanNotSeeTableRecords([$published]);
    }

    public function test_copy_url_action_runs_and_notifies(): void
    {
        $media = Media::factory()->create(['path' => 'media/contoh.png', 'mime_type' => 'image/png']);

        Livewire::test(ListMedia::class)
            ->callAction(TestAction::make('copy_url')->table($media))
            ->assertHasNoActionErrors()
            ->assertNotified();
    }

    public function test_bulk_publish_sets_show_in_gallery_true(): void
    {
        $records = Media::factory()->count(2)->create(['show_in_gallery' => false]);

        Livewire::test(ListMedia::class)
            ->callTableBulkAction('publish_selected', $records);

        foreach ($records as $record) {
            $this->assertTrue($record->refresh()->show_in_gallery);
        }
    }

    public function test_bulk_unpublish_sets_show_in_gallery_false(): void
    {
        $records = Media::factory()->count(2)->inGallery()->create();

        Livewire::test(ListMedia::class)
            ->callTableBulkAction('unpublish_selected', $records);

        foreach ($records as $record) {
            $this->assertFalse($record->refresh()->show_in_gallery);
        }
    }

    public function test_table_renders_in_every_card_size(): void
    {
        $media = Media::factory()->count(2)->create();

        foreach (['small', 'medium', 'large', 'list'] as $size) {
            Livewire::test(ListMedia::class)
                ->set('cardSize', $size)
                ->assertSuccessful()
                ->assertCanSeeTableRecords($media);
        }
    }

    public function test_card_size_action_updates_preference_and_rerenders_table(): void
    {
        $media = Media::factory()->count(2)->create();

        Livewire::test(ListMedia::class)
            ->assertSet('cardSize', 'medium')
            ->callAction(TestAction::make('card_size_list')->table())
            ->assertSet('cardSize', 'list')
            ->assertSuccessful()
            ->assertCanSeeTableRecords($media);
    }

    // ── Add embed action ──────────────────────────────────────────

    public function test_can_add_youtube_embed(): void
    {
        Livewire::test(ListMedia::class)
            ->callAction('add_embed', [
                'name' => 'Video Wisuda 2025',
                'embed_url' => 'https://youtu.be/dQw4w9WgXcQ',
            ])
            ->assertHasNoActionErrors()
            ->assertNotified();

        $this->assertDatabaseHas(Media::class, [
            'name' => 'Video Wisuda 2025',
            'embed_provider' => 'youtube',
            'embed_url' => 'https://youtu.be/dQw4w9WgXcQ',
            'path' => null,
            'show_in_gallery' => true,
        ]);
    }

    public function test_add_embed_respects_alt_and_gallery_toggle(): void
    {
        Livewire::test(ListMedia::class)
            ->callAction('add_embed', [
                'name' => 'Video Profil',
                'alt' => 'Cuplikan video profil sekolah',
                'embed_url' => 'https://youtu.be/dQw4w9WgXcQ',
                'show_in_gallery' => false,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas(Media::class, [
            'name' => 'Video Profil',
            'alt' => 'Cuplikan video profil sekolah',
            'show_in_gallery' => false,
        ]);
    }

    public function test_embed_help_action_is_available(): void
    {
        Livewire::test(ListMedia::class)
            ->assertActionExists('embed_help');
    }

    public function test_embed_help_view_explains_each_provider(): void
    {
        $html = view('filament.media.embed-help')->render();

        $this->assertStringContainsString('YouTube', $html);
        $this->assertStringContainsString('TikTok', $html);
        $this->assertStringContainsString('Instagram', $html);
        $this->assertStringContainsString('youtu.be', $html);
        $this->assertStringContainsString('tiktok.com/@', $html);
        $this->assertStringContainsString('instagram.com/reel', $html);
    }

    public function test_add_embed_rejects_unsupported_url(): void
    {
        Livewire::test(ListMedia::class)
            ->callAction('add_embed', [
                'name' => 'Bukan Video',
                'embed_url' => 'https://example.com/video/123',
            ])
            ->assertHasActionErrors(['embed_url']);

        $this->assertDatabaseCount(Media::class, 0);
    }

    public function test_add_embed_rejects_youtube_channel_without_video_id(): void
    {
        Livewire::test(ListMedia::class)
            ->callAction('add_embed', [
                'name' => 'Channel',
                'embed_url' => 'https://www.youtube.com/@somechannel',
            ])
            ->assertHasActionErrors(['embed_url']);
    }

    // ── Edit ──────────────────────────────────────────────────────

    public function test_editing_embed_url_resyncs_provider(): void
    {
        $embed = Media::factory()->embed('youtube')->create();

        Livewire::test(EditMedia::class, ['record' => $embed->id])
            ->fillForm(['embed_url' => 'https://www.tiktok.com/@scout/video/7012345678901234567'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Media::class, [
            'id' => $embed->id,
            'embed_provider' => 'tiktok',
            'embed_url' => 'https://www.tiktok.com/@scout/video/7012345678901234567',
        ]);
    }

    public function test_can_toggle_show_in_gallery(): void
    {
        $file = Media::factory()->create(['show_in_gallery' => false]);

        Livewire::test(EditMedia::class, ['record' => $file->id])
            ->fillForm(['show_in_gallery' => true])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas(Media::class, [
            'id' => $file->id,
            'show_in_gallery' => true,
        ]);
    }

    // ── Model accessors ───────────────────────────────────────────

    public function test_embed_record_exposes_url_thumbnail_and_html(): void
    {
        $embed = Media::factory()->embed('youtube')->create();

        $this->assertTrue($embed->is_embed);
        $this->assertSame('https://www.youtube.com/watch?v=dQw4w9WgXcQ', $embed->url);
        $this->assertSame('YouTube', $embed->getTypeLabel());
        $this->assertStringContainsString('img.youtube.com', $embed->embed_thumbnail);
        $this->assertStringContainsString('<iframe', (string) $embed->embed_html);
    }

    public function test_file_record_is_not_embed(): void
    {
        $file = Media::factory()->create();

        $this->assertFalse($file->is_embed);
        $this->assertNull($file->embed_thumbnail);
        $this->assertNull($file->embed_html);
    }

    public function test_origin_label_reflects_storage_folder(): void
    {
        $settings = Media::factory()->create(['path' => 'settings/logo.png']);
        $blog = Media::factory()->create(['path' => 'posts/images/foto.jpg']);
        $gallery = Media::factory()->create(['path' => 'media/foto.jpg']);

        $this->assertSame('Pengaturan', $settings->getOriginLabel());
        $this->assertSame('Blog', $blog->getOriginLabel());
        $this->assertSame('Galeri', $gallery->getOriginLabel());
    }

    public function test_origin_label_for_embed_is_provider(): void
    {
        $embed = Media::factory()->embed('youtube')->create();

        $this->assertSame('YouTube', $embed->getOriginLabel());
    }
}
