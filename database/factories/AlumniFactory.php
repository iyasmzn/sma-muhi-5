<?php

namespace Database\Factories;

use App\Models\Alumni;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alumni>
 */
class AlumniFactory extends Factory
{
    protected $model = Alumni::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $enteredPtn = fake()->boolean(60);

        return [
            'full_name' => fake()->name(),
            'nickname' => fake()->optional()->firstName(),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-30 years', '-17 years')->format('Y-m-d'),
            'address' => fake()->address(),
            'phone' => '08'.fake()->numerify('#########'),
            'major' => fake()->randomElement(['IPA', 'IPS', 'Bahasa']),
            'graduation_year' => fake()->numberBetween(2005, (int) date('Y')),
            'certificate_number' => strtoupper(fake()->unique()->bothify('DN-##-Ma/########')),
            'instagram' => fake()->optional()->userName(),
            'twitter' => fake()->optional()->userName(),
            'facebook' => fake()->optional()->name(),
            'youtube' => fake()->optional()->userName(),
            'occupation' => fake()->optional()->jobTitle(),
            'entered_ptn' => $enteredPtn,
            'ptn_name' => $enteredPtn ? 'Universitas '.fake()->city() : null,
        ];
    }

    public function enteredPtn(): static
    {
        return $this->state(fn (array $attributes): array => [
            'entered_ptn' => true,
            'ptn_name' => 'Universitas '.fake()->city(),
        ]);
    }

    public function notEnteredPtn(): static
    {
        return $this->state(fn (array $attributes): array => [
            'entered_ptn' => false,
            'ptn_name' => null,
        ]);
    }
}
