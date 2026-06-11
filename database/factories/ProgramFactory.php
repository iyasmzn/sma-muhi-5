<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    /** @var array<string> */
    private array $categories = ['Akademik', 'Ekstrakurikuler', 'Keagamaan', 'Karakter', 'Teknologi'];

    /** @var array<string> */
    private array $icons = ['📚', '⚽', '🕌', '🔬', '💻', '🎨', '🎯', '🌱'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title($this->faker->unique()->words(3, true));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1, 9999),
            'category' => $this->faker->randomElement($this->categories),
            'icon' => $this->faker->randomElement($this->icons),
            'excerpt' => $this->faker->sentence(12),
            'description' => $this->generateDescription(),
            'image' => null,
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    public function withGallery(int $count = 3): static
    {
        return $this->state(fn (): array => [
            'gallery' => array_map(
                fn (int $i): string => "programs/gallery/sample-{$i}.jpg",
                range(1, $count),
            ),
        ]);
    }

    public function draft(): static
    {
        return $this->state(['is_published' => false]);
    }

    private function generateDescription(): string
    {
        $paragraphs = $this->faker->paragraphs($this->faker->numberBetween(3, 5));
        $html = '<p>'.implode('</p><p>', $paragraphs).'</p>';

        $parts = explode('</p>', $html, 2);

        return $parts[0].'</p><h2>'.Str::title($this->faker->words(4, true)).'</h2>'.$parts[1];
    }
}
