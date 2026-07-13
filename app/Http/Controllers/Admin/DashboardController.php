<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Mail\ReservationStatusMail;
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

        $pendingList = $pendingListQuery->orderBy('tanggal')->get();

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

        $approvedList = $approvedListQuery->orderBy('tanggal')->get();

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

        $jamMulai = \Illuminate\Support\Carbon::parse($reservation->jam_mulai)->format('H:i:00');
        $jamSelesai = \Illuminate\Support\Carbon::parse($reservation->jam_selesai)->format('H:i:00');

        // Check if there is an overlapping reservation that is already approved
        $overlap = Reservation::where('room_id', $reservation->room_id)
            ->where('tanggal', $reservation->tanggal)
            ->where('status', 'approved')
            ->where('id', '!=', $reservation->id)
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        if ($overlap) {
            return redirect()->back()->with('error', 'Reservasi ini tidak dapat disetujui karena bentrok dengan reservasi lain yang sudah disetujui.');
        }

        $reservation->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        // Kirim email notifikasi ke dosen
        $reservation->load(['room', 'user']);
        try {
            Mail::to($reservation->user->email)->send(new ReservationStatusMail($reservation));
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

        $reservation->update([
            'status' => 'rejected',
            'alasan_penolakan' => $request->alasan_penolakan,
            'approved_by' => auth()->id(),
        ]);

        // Kirim email notifikasi ke dosen
        $reservation->load(['room', 'user']);
        try {
            Mail::to($reservation->user->email)->send(new ReservationStatusMail($reservation));
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email status ditolak #' . $reservation->id . ': ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Reservasi berhasil ditolak.');
    }
}
