<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Equipment;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = (clone $weekStart)->addWeek();

        $stats = [
            'rooms_count' => Room::count(),
            'equipment_count' => Equipment::count(),
            'pending_count' => Booking::where('status', 'en_attente')->count(),
            'bookings_this_week' => Booking::where('starts_at', '>=', $weekStart)
                ->where('starts_at', '<', $weekEnd)
                ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
