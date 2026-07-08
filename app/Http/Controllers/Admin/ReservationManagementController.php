<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

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

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->back()->with('success', 'Data reservasi berhasil dihapus.');
    }
}
