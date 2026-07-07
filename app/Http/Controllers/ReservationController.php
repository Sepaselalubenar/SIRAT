<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Lantai yang butuh approval admin + minimal booking H+2.
     * Lantai lain otomatis approved kalau ruangan kosong di tanggal itu.
     */
    private const LANTAI_APPROVAL = '19';
    private const MIN_HARI_LANTAI_APPROVAL = 2;

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal' => 'required|date|after:today',
            'tujuan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();
        $room = Room::findOrFail($data['room_id']);

        $this->pastikanTidakBentrokTanggalDosen($user->id, $data['tanggal']);
        $this->pastikanRuanganKosong($room->id, $data['tanggal']);

        $butuhApproval = $room->lantai == self::LANTAI_APPROVAL;

        if ($butuhApproval) {
            $this->pastikanMinimalHPlus2($data['tanggal']);
        }

        Reservation::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'tanggal' => $data['tanggal'],
            'tujuan' => $data['tujuan'],
            'keterangan' => $data['keterangan'] ?? null,
            // Lantai 19 wajib approval admin, lantai lain langsung disetujui kalau kosong.
            'status' => $butuhApproval ? 'pending' : 'approved',
        ]);

        return redirect('/history')
            ->with('success', $butuhApproval
                ? 'Reservasi berhasil diajukan, menunggu approval admin.'
                : 'Reservasi berhasil dan otomatis disetujui.');
    }

    /**
     * Satu dosen hanya boleh punya satu reservasi (pending/approved) pada tanggal yang sama.
     */
    private function pastikanTidakBentrokTanggalDosen(int $userId, string $tanggal): void
    {
        $sudahAda = Reservation::where('user_id', $userId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($sudahAda) {
            throw ValidationException::withMessages([
                'tanggal' => 'Anda sudah memiliki reservasi lain pada tanggal ini.',
            ]);
        }
    }

    /**
     * Ruangan yang sama tidak boleh dipesan dua kali (pending/approved) di tanggal yang sama.
     */
    private function pastikanRuanganKosong(int $roomId, string $tanggal): void
    {
        $sudahDipesan = Reservation::where('room_id', $roomId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($sudahDipesan) {
            throw ValidationException::withMessages([
                'room_id' => 'Ruangan ini sudah dipesan pada tanggal tersebut.',
            ]);
        }
    }

    /**
     * Ruangan di lantai yang butuh approval minimal dipesan H+2 dari hari ini.
     */
    private function pastikanMinimalHPlus2(string $tanggal): void
    {
        $minimalTanggal = now()->addDays(self::MIN_HARI_LANTAI_APPROVAL)->startOfDay();

        if (now()->parse($tanggal)->lt($minimalTanggal)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Untuk ruangan ini, reservasi minimal H+' . self::MIN_HARI_LANTAI_APPROVAL . ' (hari ini ' . now()->translatedFormat('d F Y') . ', bisa reservasi mulai ' . $minimalTanggal->translatedFormat('d F Y') . ').',
            ]);
        }
    }
}
