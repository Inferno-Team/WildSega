<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plant>
 */
class PlantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'common_name' => fake()->name(),
            'scientific_name' => fake()->name(),
            'description' => fake()->text(),
            'safety_notes' => fake()->text(),
            'harvesting_tips' => fake()->text(),
        ];
    }
}
