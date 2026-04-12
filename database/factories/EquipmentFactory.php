<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $names = ['Vidéoprojecteur', 'Tableau interactif', 'Micro', 'Laptop'];

        return [
            'name' => fake()->randomElement($names),
            'quantity' => fake()->numberBetween(1, 10),
            'is_available' => true,
        ];
    }
}
