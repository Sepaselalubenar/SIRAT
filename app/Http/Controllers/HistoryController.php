<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $reservations = Auth::user()
            ->reservations()
            ->with('room')
            ->orderByDesc('tanggal')
            ->get();

        return view('lecturer.history', compact('reservations'));
    }
}
