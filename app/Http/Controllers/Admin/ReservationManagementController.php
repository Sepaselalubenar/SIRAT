<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Mail\ReservationCancelledMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReservationManagementController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'tanggal');
        $order = $request->get('order', 'desc');

        // Validate allowed sort fields and directions
        $allowedSorts = ['room', 'user', 'created_at', 'tanggal', 'waktu', 'tujuan', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }

        $query = Reservation::with(['room', 'user']);
        $user = auth()->user();
        if ($user->admin_type === 1) {
            $query->whereHas('room', function($q) {
                $q->where('lantai', '!=', '19');
            });
        } elseif ($user->admin_type === 2) {
            $query->whereHas('room', function($q) {
                $q->where('lantai', '19');
            });
        }

        switch ($sortBy) {
            case 'room':
                $query->join('rooms', 'reservations.room_id', '=', 'rooms.id')
                    ->select('reservations.*')
                    ->orderBy('rooms.nama', $order);
                break;
            case 'user':
                $query->join('users', 'reservations.user_id', '=', 'users.id')
                    ->select('reservations.*')
                    ->orderBy('users.name', $order);
                break;
            case 'created_at':
                $query->orderBy('reservations.created_at', $order);
                break;
            case 'waktu':
                $query->orderBy('reservations.jam_mulai', $order);
                break;
            case 'tujuan':
                $query->orderBy('reservations.tujuan', $order);
                break;
            case 'status':
                $query->orderBy('reservations.status', $order);
                break;
            case 'tanggal':
            default:
                $query->orderBy('reservations.tanggal', $order)
                    ->orderBy('reservations.jam_mulai', $order);
                break;
        }

        $reservations = $query->get();

        return view('admin.reservations.index', compact('reservations', 'sortBy', 'order'));
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        $reservation = Reservation::findOrFail($id);

        if (!auth()->user()->canManageReservation($reservation)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membatalkan reservasi ini.');
        }

        $reservation->update([
            'status'            => 'cancelled',
            'alasan_pembatalan' => $request->alasan_pembatalan,
            'approved_by'       => auth()->id(),
        ]);

        // Kirim email notifikasi ke dosen
        $reservation->load(['room', 'user']);
        try {
            Mail::to($reservation->user->email)->send(new ReservationCancelledMail($reservation));
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email pembatalan reservasi #' . $reservation->id . ': ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan dan email notifikasi telah dikirim.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (!auth()->user()->canManageReservation($reservation)) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus reservasi ini.');
        }

        $reservation->delete();

        return redirect()->back()->with('success', 'Data reservasi berhasil dihapus.');
    }
}
