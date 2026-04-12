<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $buildings = ['Bâtiment A', 'Bâtiment B', 'Bâtiment C'];

        return [
            'name' => 'Salle '.fake()->unique()->bothify('###'),
            'capacity' => fake()->numberBetween(20, 100),
            'building' => fake()->randomElement($buildings),
            'is_available' => true,
        ];
    }
}
