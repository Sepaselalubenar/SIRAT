<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalReservations = $user->reservations()->count();
        $pendingReservations = $user->reservations()->where('status', 'pending')->count();
        $approvedReservations = $user->reservations()->where('status', 'approved')->count();

        // Get recent reservations (latest 5)
        $recentReservations = $user->reservations()
            ->with('room')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('lecturer.dashboard', compact(
            'totalReservations',
            'pendingReservations',
            'approvedReservations',
            'recentReservations'
        ));
    }
}
