<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Mail\ReservationStatusMail;
use App\Mail\MultiDayReservationStatusMail;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $roomQuery = Room::query();
        $pendingQuery = Reservation::where('status', 'pending');
        $approvedQuery = Reservation::where('status', 'approved');

        if ($user->admin_type === 1) {
            $roomQuery->where('lantai', '!=', '19');
            $pendingQuery->whereHas('room', function ($q) {
                $q->where('lantai', '!=', '19');
            });
            $approvedQuery->whereHas('room', function ($q) {
                $q->where('lantai', '!=', '19');
            });
        } elseif ($user->admin_type === 2) {
            $roomQuery->where('lantai', '19');
            $pendingQuery->whereHas('room', function ($q) {
                $q->where('lantai', '19');
            });
            $approvedQuery->whereHas('room', function ($q) {
                $q->where('lantai', '19');
            });
        }

        $totalRooms = $roomQuery->count();
        $pendingReservations = $pendingQuery->count();
        $approvedReservations = $approvedQuery->count();

        $pendingListQuery = Reservation::with(['room', 'user'])
            ->where('status', 'pending');

        if ($user->admin_type === 1) {
            $pendingListQuery->whereHas('room', function ($q) {
                $q->where('lantai', '!=', '19');
            });
        } elseif ($user->admin_type === 2) {
            $pendingListQuery->whereHas('room', function ($q) {
                $q->where('lantai', '19');
            });
        }

        $pendingRaw = $pendingListQuery->orderBy('tanggal')->get();
        $pendingList = [];
        $pendingGroups = $pendingRaw->groupBy(function ($item) {
            return $item->group_id ?? 'single_' . $item->id;
        });

        foreach ($pendingGroups as $key => $items) {
            $main = $items->first();
            $main->is_group = !str_starts_with($key, 'single_');
            $main->group_dates = $items->pluck('tanggal')->toArray();
            $main->group_reservations = $items;
            $pendingList[] = $main;
        }

        // Ruangan yang sedang/akan dipakai (reservasi approved, tanggal hari ini atau ke depan).
        $approvedListQuery = Reservation::with(['room', 'user'])
            ->where('status', 'approved')
            ->whereDate('tanggal', '>=', now()->toDateString());

        if ($user->admin_type === 1) {
            $approvedListQuery->whereHas('room', function ($q) {
                $q->where('lantai', '!=', '19');
            });
        } elseif ($user->admin_type === 2) {
            $approvedListQuery->whereHas('room', function ($q) {
                $q->where('lantai', '19');
            });
        }

        $approvedRaw = $approvedListQuery->orderBy('tanggal')->get();
        $approvedList = [];
        $approvedGroups = $approvedRaw->groupBy(function ($item) {
            return $item->group_id ?? 'single_' . $item->id;
        });

        foreach ($approvedGroups as $key => $items) {
            $main = $items->first();
            $main->is_group = !str_starts_with($key, 'single_');
            $main->group_dates = $items->pluck('tanggal')->toArray();
            $main->group_reservations = $items;
            $approvedList[] = $main;
        }

        return view('admin.dashboard', compact(
            'totalRooms',
            'pendingReservations',
            'approvedReservations',
            'pendingList',
            'approvedList'
        ));
    }

    public function approve($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!auth()->user()->canManageReservation($reservation)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menyetujui reservasi ini.');
        }

        $reservationsToApprove = $reservation->group_id
            ? Reservation::where('group_id', $reservation->group_id)->where('status', 'pending')->get()
            : collect([$reservation]);

        $bentrokDates = [];
        foreach ($reservationsToApprove as $res) {
            $jamMulai = \Illuminate\Support\Carbon::parse($res->jam_mulai)->format('H:i:00');
            $jamSelesai = \Illuminate\Support\Carbon::parse($res->jam_selesai)->format('H:i:00');

            $overlap = Reservation::where('room_id', $res->room_id)
                ->where('tanggal', $res->tanggal)
                ->where('status', 'approved')
                ->where('id', '!=', $res->id)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists();

            if ($overlap) {
                $bentrokDates[] = \Illuminate\Support\Carbon::parse($res->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            }
        }

        if (!empty($bentrokDates)) {
            return redirect()->back()->with('error', 'Reservasi tidak dapat disetujui karena bentrok pada tanggal berikut: ' . implode(', ', $bentrokDates) . '.');
        }

        foreach ($reservationsToApprove as $res) {
            $res->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);
        }

        // Kirim email notifikasi ke dosen
        try {
            if ($reservation->group_id) {
                $reservationsToApprove->load(['room', 'user']);
                Mail::to($reservation->user->email)->send(new MultiDayReservationStatusMail($reservationsToApprove, 'approved'));
            } else {
                $reservation->load(['room', 'user']);
                Mail::to($reservation->user->email)->send(new ReservationStatusMail($reservation));
            }
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email status disetujui #' . $reservation->id . ': ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Reservasi berhasil disetujui.');
    }

    public function reject(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $reservation = Reservation::findOrFail($id);

        if (!auth()->user()->canManageReservation($reservation)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menolak reservasi ini.');
        }

        $reservationsToReject = $reservation->group_id
            ? Reservation::where('group_id', $reservation->group_id)->where('status', 'pending')->get()
            : collect([$reservation]);

        foreach ($reservationsToReject as $res) {
            $res->update([
                'status' => 'rejected',
                'alasan_penolakan' => $request->alasan_penolakan,
                'approved_by' => auth()->id(),
            ]);
        }

        // Kirim email notifikasi ke dosen
        try {
            if ($reservation->group_id) {
                $reservationsToReject->load(['room', 'user']);
                Mail::to($reservation->user->email)->send(new MultiDayReservationStatusMail($reservationsToReject, 'rejected'));
            } else {
                $reservation->load(['room', 'user']);
                Mail::to($reservation->user->email)->send(new ReservationStatusMail($reservation));
            }
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email status ditolak #' . $reservation->id . ': ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Reservasi berhasil ditolak.');
    }
}
