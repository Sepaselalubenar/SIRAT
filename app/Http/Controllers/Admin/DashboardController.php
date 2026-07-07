<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $approvedReservations = Reservation::where('status', 'approved')->count();

        $pendingList = Reservation::with(['room', 'user'])
            ->where('status', 'pending')
            ->orderBy('tanggal')
            ->get();

        // Ruangan yang sedang/akan dipakai (reservasi approved, tanggal hari ini atau ke depan).
        $approvedList = Reservation::with(['room', 'user'])
            ->where('status', 'approved')
            ->whereDate('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->get();

        return view('admin.dashboard', compact(
            'totalRooms',
            'pendingReservations',
            'approvedReservations',
            'pendingList',
            'approvedList'
        ));
    }
}
