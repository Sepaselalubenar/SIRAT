<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        $reservationsRaw = Auth::user()
            ->reservations()
            ->with('room')
            ->orderByDesc('tanggal')
            ->get();

        $reservations = [];
        $groups = $reservationsRaw->groupBy(function ($item) {
            return $item->group_id ?? 'single_' . $item->id;
        });

        foreach ($groups as $key => $items) {
            $main = $items->first();
            $main->is_group = !str_starts_with($key, 'single_');
            $main->group_dates = $items->pluck('tanggal')->toArray();
            $main->group_reservations = $items;
            $reservations[] = $main;
        }

        $reservations = collect($reservations);

        return view('lecturer.history', compact('reservations'));
    }
}
