<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Media;
use App\Models\User;
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
        ]);
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
}
