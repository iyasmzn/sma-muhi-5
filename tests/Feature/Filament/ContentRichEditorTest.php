<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Programs\Pages\CreateProgram;
use App\Filament\Resources\StaticPages\Pages\CreateStaticPage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContentRichEditorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    /**
     * Rendering each form resolves every toolbar button, including the custom
     * `fullscreen` tool; an unknown button name would throw during render.
     */
    public function test_post_create_page_renders_content_editor(): void
    {
        Livewire::test(CreatePost::class)
            ->assertSuccessful();
    }

    public function test_program_create_page_renders_content_editor(): void
    {
        Livewire::test(CreateProgram::class)
            ->assertSuccessful();
    }

    public function test_static_page_create_page_renders_content_editor(): void
    {
        Livewire::test(CreateStaticPage::class)
            ->assertSuccessful();
    }
}
