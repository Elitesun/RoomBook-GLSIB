<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('+1 day', '+10 days');
        $endsAt = (clone $startsAt)->modify('+2 hours');

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'purpose' => fake()->sentence(),
            'status' => 'en_attente',
            'rejection_reason' => null,
        ];
    }
}
