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
        $query = Room::orderBy('lantai')->orderBy('nama');
        if (auth()->check() && auth()->user()->isAdmin()) {
            if (auth()->user()->admin_type === 1) {
                $query->where('lantai', '!=', '19');
            } elseif (auth()->user()->admin_type === 2) {
                $query->where('lantai', '19');
            }
        }
        $rooms = $query->get();
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
            ->where(function($q) {
                $q->where('status', 'approved')
                  ->orWhere(function($sub) {
                      $sub->where('status', 'pending');
                      if (auth()->check()) {
                          if (auth()->user()->isAdmin()) {
                              // Admins see all pending
                          } else {
                              // Regular users only see their own pending
                              $sub->where('user_id', auth()->id());
                          }
                      } else {
                          // Guests see no pending
                          $sub->whereRaw('1 = 0');
                      }
                  });
            });

        if ($roomId && $roomId !== 'all') {
            $query->where('room_id', $roomId);
        } elseif (auth()->check() && auth()->user()->isAdmin()) {
            if (auth()->user()->admin_type === 1) {
                $query->whereHas('room', function($q) {
                    $q->where('lantai', '!=', '19');
                });
            } elseif (auth()->user()->admin_type === 2) {
                $query->whereHas('room', function($q) {
                    $q->where('lantai', '19');
                });
            }
        }

        $reservations = $query->orderBy('jam_mulai')->get();

        return response()->json($reservations->map(function ($r) {
            try {
                $tgl = Carbon::parse($r->tanggal)->format('Y-m-d');
                $jam = substr($r->jam_selesai, 0, 5);
                $isPast = Carbon::parse("{$tgl} {$jam}")->isPast();
            } catch (\Throwable $e) {
                $isPast = false;
            }

            return [
                'id'          => $r->id,
                'room_id'     => $r->room_id,
                'room_name'   => $r->room?->nama ?? '-',
                'room_lantai' => $r->room?->lantai ?? '-',
                'user_name'   => $r->user?->name ?? '-',
                'tanggal'     => is_string($r->tanggal) ? $r->tanggal : Carbon::parse($r->tanggal)->format('Y-m-d'),
                'jam_mulai'   => substr($r->jam_mulai, 0, 5),
                'jam_selesai' => substr($r->jam_selesai, 0, 5),
                'tujuan'      => $r->tujuan,
                'keterangan'  => $r->keterangan,
                'status'      => $r->status,
                'is_past'     => $isPast,
            ];
        }));
    }
}
