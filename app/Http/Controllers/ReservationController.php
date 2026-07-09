<?php

namespace App\Http\Controllers;

use App\Mail\ReservationSuccessMail;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    /**
     * Lantai yang butuh approval admin + minimal booking H+2.
     * Lantai lain otomatis approved kalau ruangan kosong di jam yang sama.
     */
    private const LANTAI_APPROVAL = '19';
    private const MIN_HARI_LANTAI_APPROVAL = 2;

    // Jam operasional gedung.
    private const JAM_BUKA = '07:00';
    private const JAM_TUTUP = '18:30';

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tanggal' => 'required|date|after:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'tujuan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:200',
        ]);

        $user = Auth::user();
        $room = Room::findOrFail($data['room_id']);

        $jamMulai = Carbon::parse($data['jam_mulai']);
        $jamSelesai = Carbon::parse($data['jam_selesai']);

        $this->pastikanDalamJamOperasional($jamMulai, $jamSelesai);
        $this->pastikanTidakBentrokTanggalDosen($user->id, $data['tanggal']);
        $this->pastikanRuanganKosong($room->id, $data['tanggal'], $jamMulai, $jamSelesai);

        $butuhApproval = (string) $room->lantai === self::LANTAI_APPROVAL;

        if ($butuhApproval) {
            $this->pastikanMinimalHPlus2($data['tanggal']);
        }

        $reservation = Reservation::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'tanggal' => $data['tanggal'],
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'tujuan' => $data['tujuan'],
            'keterangan' => $data['keterangan'] ?? null,
            // Lantai 19 wajib approval admin, lantai lain langsung disetujui kalau kosong.
            'status' => $butuhApproval ? 'pending' : 'approved',
        ]);

        // Kirim email notifikasi ke dosen.
        // Muat relasi room & user agar tersedia di template email.
        $reservation->load(['room', 'user']);

        try {
            Mail::to($reservation->user->email)->send(new ReservationSuccessMail($reservation));
        } catch (\Throwable $e) {
            // Tangkap error API agar reservasi tetap tersimpan walau email gagal terkirim.
            logger()->error('Gagal mengirim email notifikasi reservasi #' . $reservation->id . ': ' . $e->getMessage());
        }

        return redirect('/history')
            ->with('success', $butuhApproval
                ? 'Reservasi berhasil diajukan, menunggu approval admin. Email konfirmasi telah dikirim.'
                : 'Reservasi berhasil dan otomatis disetujui. Email konfirmasi telah dikirim.');
    }

    /**
     * Jam mulai & selesai harus dalam jam operasional gedung (08.00 - 17.00),
     * dan jam selesai harus setelah jam mulai.
     */
    private function pastikanDalamJamOperasional(Carbon $jamMulai, Carbon $jamSelesai): void
    {
        $buka = Carbon::parse(self::JAM_BUKA);
        $tutup = Carbon::parse(self::JAM_TUTUP);

        if ($jamMulai->lt($buka) || $jamSelesai->gt($tutup)) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Reservasi hanya bisa dilakukan dalam jam operasional ' . self::JAM_BUKA . ' - ' . self::JAM_TUTUP . '.',
            ]);
        }

        if ($jamSelesai->lte($jamMulai)) {
            throw ValidationException::withMessages([
                'jam_selesai' => 'Jam selesai harus setelah jam mulai.',
            ]);
        }
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
     * Ruangan yang sama tidak boleh dipesan di jam yang tumpang tindih pada tanggal yang sama.
     * Dua rentang waktu dianggap bentrok kalau: mulaiA < selesaiB DAN selesaiA > mulaiB.
     */
    private function pastikanRuanganKosong(int $roomId, string $tanggal, Carbon $jamMulai, Carbon $jamSelesai): void
    {
        $bentrok = Reservation::where('room_id', $roomId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'approved'])
            ->where('jam_mulai', '<', $jamSelesai->format('H:i'))
            ->where('jam_selesai', '>', $jamMulai->format('H:i'))
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Ruangan ini sudah dipesan pada rentang jam tersebut. Silakan pilih jam lain.',
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