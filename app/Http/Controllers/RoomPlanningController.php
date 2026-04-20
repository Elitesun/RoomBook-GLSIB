<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RoomPlanningController extends Controller
{
    public function index(): View
    {
        [$weekStart, $weekEnd, $weekValue] = $this->resolveWeek();

        $rooms = Room::query()
            ->with(['bookings' => function ($query) use ($weekStart, $weekEnd): void {
                $query->where('starts_at', '<', $weekEnd)
                    ->where('ends_at', '>', $weekStart)
                    ->with('user')
                    ->orderBy('starts_at');
            }])
            ->orderBy('name')
            ->get();

        $days = collect(range(0, 6))->map(fn (int $offset) => (clone $weekStart)->addDays($offset));

        return view('planning.index', [
            'rooms' => $rooms,
            'days' => $days,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'weekValue' => $weekValue,
            'showAllStatuses' => false,
            'pageTitle' => 'Planning des salles',
        ]);
    }

    private function resolveWeek(): array
    {
        $requestedWeek = request()->query('week');

        if (is_string($requestedWeek) && preg_match('/^(\\d{4})-(\\d{2})$/', $requestedWeek, $matches) === 1) {
            $year = (int) $matches[1];
            $week = (int) $matches[2];
            $weekStart = Carbon::now()->setISODate($year, $week)->startOfWeek();
        } else {
            $weekStart = Carbon::now()->startOfWeek();
        }

        $weekEnd = (clone $weekStart)->copy()->addWeek();

        return [$weekStart, $weekEnd, $weekStart->format('o-W')];
    }
}
