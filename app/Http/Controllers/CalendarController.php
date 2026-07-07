<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('lantai')->orderBy('nama')->get();
        return view('calendar.index', compact('rooms'));
    }

    public function events(Request $request)
    {
        $roomId = $request->get('room_id');
        $dateStr = $request->get('date', now()->toDateString());

        try {
            $date = Carbon::parse($dateStr)->toDateString();
        } catch (\Exception $e) {
            $date = now()->toDateString();
        }

        $query = Reservation::with(['room', 'user'])
            ->where('tanggal', $date)
            ->whereIn('status', ['approved', 'pending']);

        if ($roomId && $roomId !== 'all') {
            $query->where('room_id', $roomId);
        }

        $reservations = $query->orderBy('jam_mulai')->get();

        return response()->json($reservations->map(function ($r) {
            return [
                'id'          => $r->id,
                'room_id'     => $r->room_id,
                'room_name'   => $r->room?->nama ?? '-',
                'room_lantai' => $r->room?->lantai ?? '-',
                'user_name'   => $r->user?->name ?? '-',
                'tanggal'     => $r->tanggal,
                'jam_mulai'   => substr($r->jam_mulai, 0, 5),
                'jam_selesai' => substr($r->jam_selesai, 0, 5),
                'tujuan'      => $r->tujuan,
                'keterangan'  => $r->keterangan,
                'status'      => $r->status,
            ];
        }));
    }
}
