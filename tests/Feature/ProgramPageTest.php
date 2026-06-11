<?php

namespace Tests\Feature;

use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_lists_published_programs(): void
    {
        $published = Program::factory()->create(['title' => 'Program Tayang']);
        $draft = Program::factory()->draft()->create(['title' => 'Program Draft']);

        $this->get(route('programs.index'))
            ->assertOk()
            ->assertSee('Program Tayang')
            ->assertDontSee('Program Draft');
    }

    public function test_index_page_can_filter_by_category(): void
    {
        $academic = Program::factory()->create(['title' => 'Olimpiade Sains', 'category' => 'Akademik']);
        $sport = Program::factory()->create(['title' => 'Futsal', 'category' => 'Ekstrakurikuler']);

        $this->get(route('programs.index', ['category' => 'Akademik']))
            ->assertOk()
            ->assertSee('Olimpiade Sains')
            ->assertDontSee('Futsal');
    }

    public function test_show_page_renders_published_program(): void
    {
        $program = Program::factory()->create([
            'title' => 'Tahfizh Quran',
            'slug' => 'tahfizh-quran',
        ]);

        $this->get(route('programs.show', $program->slug))
            ->assertOk()
            ->assertSee('Tahfizh Quran');
    }

    public function test_show_page_returns_404_for_draft_program(): void
    {
        $program = Program::factory()->draft()->create(['slug' => 'program-draft']);

        $this->get(route('programs.show', $program->slug))
            ->assertNotFound();
    }

    public function test_show_page_returns_404_for_unknown_slug(): void
    {
        $this->get(route('programs.show', 'tidak-ada'))
            ->assertNotFound();
    }

    public function test_home_page_shows_only_featured_programs(): void
    {
        $featured = Program::factory()->featured()->create(['title' => 'Program Unggulan']);
        $regular = Program::factory()->create(['title' => 'Program Biasa']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Program Unggulan');
    }
}
