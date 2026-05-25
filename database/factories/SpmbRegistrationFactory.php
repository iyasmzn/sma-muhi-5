<?php

namespace Database\Factories;

use App\Models\SpmbRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SpmbRegistration>
 */
class SpmbRegistrationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '08'.fake()->numerify('#########'),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'birth_place' => fake()->city(),
            'previous_school' => 'SMP '.fake()->company(),
            'previous_school_city' => fake()->city(),
            'address' => fake()->address(),
            'jalur' => fake()->randomElement(['zonasi', 'prestasi', 'afirmasi', 'mutasi']),
            'parent_name' => fake()->name(),
            'parent_phone' => '08'.fake()->numerify('#########'),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'verified', 'accepted', 'rejected']),
            'verified_at' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending', 'verified_at' => null]);
    }

    public function accepted(): static
    {
        return $this->state(['status' => 'accepted', 'verified_at' => now()]);
    }
}
