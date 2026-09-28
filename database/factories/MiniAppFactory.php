<?php

namespace Database\Factories;

use App\Models\MiniApp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MiniAppFactory extends Factory
{
    protected $model = MiniApp::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(10),
            'creator_id' => User::factory(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'maintenance']),
            'version' => $this->faker->numerify('#.#.#'),
            'api_rate_limit' => $this->faker->randomElement([30, 60, 120, 240]),
            'webhook_url' => $this->faker->optional()->url(),
            'meta' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}
