<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slide>
 */
class SlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'slides/'.fake()->uuid().'.jpg',
            'title' => fake()->sentence(3),
            'subtitle' => fake()->sentence(6),
            'button_label' => null,
            'button_url' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
