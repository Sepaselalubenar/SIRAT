<?php

namespace App\Http\Controllers;

use App\Mail\ReservationSuccessMail;
use App\Mail\MultiDayReservationSuccessMail;
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
        $tipeReservasi = $request->input('tipe_reservasi') ?: 'biasa';

        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'tipe_reservasi' => 'nullable|in:biasa,sehari_penuh',
            'tujuan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:200',
        ];

        if ($tipeReservasi === 'sehari_penuh') {
            $rules['tanggal_mulai'] = 'required|date|after_or_equal:today';
            $rules['tanggal_selesai'] = 'required|date|after_or_equal:tanggal_mulai';
        } else {
            $rules['tanggal'] = 'required|date|after_or_equal:today';
            $rules['jam_mulai'] = 'required|date_format:H:i';
            $rules['jam_selesai'] = 'required|date_format:H:i';
        }

        $data = $request->validate($rules);

        $user = Auth::user();
        $room = Room::findOrFail($data['room_id']);
        $butuhApproval = (string) $room->lantai === self::LANTAI_APPROVAL;
        $dates = [];
        if ($tipeReservasi === 'sehari_penuh') {
            $startDate = Carbon::parse($data['tanggal_mulai']);
            $endDate = Carbon::parse($data['tanggal_selesai']);

            if ($startDate->diffInDays($endDate) > 14) {
                throw ValidationException::withMessages([
                    'tanggal_selesai' => 'Reservasi sehari penuh maksimal dapat dilakukan untuk 14 hari sekaligus.',
                ]);
            }

            for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                if ($d->isSunday()) {
                    continue;
                }
                $dates[] = $d->toDateString();
            }

            if (empty($dates)) {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Pemesanan ditutup untuk hari Minggu. Silakan sesuaikan rentang tanggal Anda.',
                ]);
            }

            $jamMulai = Carbon::parse(self::JAM_BUKA);
            $jamSelesai = Carbon::parse(self::JAM_TUTUP);
        } else {
            $dateParsed = Carbon::parse($data['tanggal']);
            if ($dateParsed->isSunday()) {
                throw ValidationException::withMessages([
                    'tanggal' => 'Pemesanan ditutup untuk hari Minggu.',
                ]);
            }
            $dates[] = $dateParsed->toDateString();
            $jamMulai = Carbon::parse($data['jam_mulai']);
            $jamSelesai = Carbon::parse($data['jam_selesai']);
        }

        // Pastikan dalam jam operasional
        $this->pastikanDalamJamOperasional($jamMulai, $jamSelesai);

        $bentrokDates = [];
        foreach ($dates as $date) {
            // Pastikan jam mulai belum lewat
            $this->pastikanJamMulaiBelumLewat($date, $jamMulai);

            // Pastikan minimal H+2 untuk lantai approval
            if ($butuhApproval) {
                $this->pastikanMinimalHPlus2($date);
            }

            // Pastikan ruangan kosong
            try {
                $this->pastikanRuanganKosong($room->id, $date, $jamMulai, $jamSelesai);
            } catch (ValidationException $e) {
                $bentrokDates[] = Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            }
        }

        if (!empty($bentrokDates)) {
            $errKey = ($tipeReservasi === 'sehari_penuh') ? 'tanggal_mulai' : 'jam_mulai';
            throw ValidationException::withMessages([
                $errKey => 'Ruangan tidak tersedia (sudah dipesan) pada hari berikut: ' . implode(', ', $bentrokDates) . '.',
            ]);
        }

        $reservations = [];
        \Illuminate\Support\Facades\DB::transaction(function () use ($room, $user, $dates, $jamMulai, $jamSelesai, $data, $butuhApproval, &$reservations) {
            foreach ($dates as $date) {
                $reservations[] = Reservation::create([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'tanggal' => $date,
                    'jam_mulai' => $jamMulai->format('H:i'),
                    'jam_selesai' => $jamSelesai->format('H:i'),
                    'tujuan' => $data['tujuan'],
                    'keterangan' => $butuhApproval ? null : ($data['keterangan'] ?? null),
                    // Lantai 19 wajib approval admin, lantai lain langsung disetujui kalau kosong.
                    'status' => $butuhApproval ? 'pending' : 'approved',
                ]);
            }
        });

        // Kirim email notifikasi ke dosen
        try {
            $userEmail = $user->email;
            if ($tipeReservasi === 'sehari_penuh') {
                $reservationsCol = collect($reservations)->each->load(['room', 'user']);
                Mail::to($userEmail)->send(new MultiDayReservationSuccessMail($reservationsCol));
            } else {
                $res = $reservations[0];
                $res->load(['room', 'user']);
                Mail::to($userEmail)->send(new ReservationSuccessMail($res));
            }
        } catch (\Throwable $e) {
            logger()->error('Gagal mengirim email notifikasi reservasi: ' . $e->getMessage());
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
     * Untuk tanggal hari ini, jam mulai tidak boleh sudah lewat dari waktu sekarang.
     */
    private function pastikanJamMulaiBelumLewat(string $tanggal, Carbon $jamMulai): void
    {
        $tanggalReservasi = Carbon::parse($tanggal)->toDateString();
        $mulaiReservasi = Carbon::parse($tanggalReservasi . ' ' . $jamMulai->format('H:i'));

        if ($mulaiReservasi->lte(now())) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Jam mulai reservasi sudah lewat. Untuk reservasi hari ini, pilih jam setelah waktu sekarang.',
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
            ->where('jam_mulai', '<', $jamSelesai->format('H:i:00'))
            ->where('jam_selesai', '>', $jamMulai->format('H:i:00'))
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
