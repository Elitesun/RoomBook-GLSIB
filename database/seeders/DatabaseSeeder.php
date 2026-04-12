<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Equipment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin RoomBook',
            'email' => 'admin@roombook.tg',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $responsables = User::factory()
            ->count(2)
            ->sequence(
                ['name' => 'Responsable 1', 'email' => 'responsable1@roombook.tg', 'role' => 'responsable'],
                ['name' => 'Responsable 2', 'email' => 'responsable2@roombook.tg', 'role' => 'responsable'],
            )
            ->create(['password' => 'password']);

        $enseignants = User::factory()
            ->count(5)
            ->sequence(
                ['name' => 'Enseignant 1', 'email' => 'enseignant1@roombook.tg', 'role' => 'enseignant'],
                ['name' => 'Enseignant 2', 'email' => 'enseignant2@roombook.tg', 'role' => 'enseignant'],
                ['name' => 'Enseignant 3', 'email' => 'enseignant3@roombook.tg', 'role' => 'enseignant'],
                ['name' => 'Enseignant 4', 'email' => 'enseignant4@roombook.tg', 'role' => 'enseignant'],
                ['name' => 'Enseignant 5', 'email' => 'enseignant5@roombook.tg', 'role' => 'enseignant'],
            )
            ->create(['password' => 'password']);

        $rooms = collect([
            ['name' => 'Salle A101', 'capacity' => 20, 'building' => 'Bâtiment A'],
            ['name' => 'Salle A202', 'capacity' => 35, 'building' => 'Bâtiment A'],
            ['name' => 'Salle B110', 'capacity' => 50, 'building' => 'Bâtiment B'],
            ['name' => 'Amphi C1', 'capacity' => 80, 'building' => 'Bâtiment C'],
            ['name' => 'Salle Info', 'capacity' => 60, 'building' => 'Bâtiment B'],
        ])->map(fn (array $room) => Room::create($room + ['is_available' => true]));

        $equipmentItems = collect([
            ['name' => 'Vidéoprojecteur', 'quantity' => 5],
            ['name' => 'Tableau interactif', 'quantity' => 3],
            ['name' => 'Micro', 'quantity' => 10],
            ['name' => 'Laptop', 'quantity' => 8],
        ])->map(fn (array $item) => Equipment::create($item + ['is_available' => true]));

        $statuses = ['en_attente', 'acceptee', 'refusee', 'annulee'];
        $baseDate = Carbon::now()->startOfWeek()->addDays(1)->setTime(8, 0);

        for ($index = 0; $index < 12; $index++) {
            $startsAt = (clone $baseDate)->addDays($index % 5)->addHours(intdiv($index, 5) * 2);
            $endsAt = (clone $startsAt)->addHours(2);
            $status = $statuses[$index % count($statuses)];
            $booking = Booking::create([
                'user_id' => $enseignants[$index % $enseignants->count()]->id,
                'room_id' => $rooms[$index % $rooms->count()]->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'purpose' => 'Réservation de démonstration '.($index + 1),
                'status' => $status,
                'rejection_reason' => $status === 'refusee' ? 'Créneau déjà utilisé pour un autre cours.' : null,
            ]);

            $requestedEquipment = $equipmentItems[$index % $equipmentItems->count()];
            $booking->equipment()->attach($requestedEquipment->id, [
                'quantity' => min(2, $requestedEquipment->quantity),
            ]);
        }
    }
}
