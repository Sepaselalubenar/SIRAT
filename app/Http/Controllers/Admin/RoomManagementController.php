<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomManagementController extends Controller
{
    private function authorizeRoom(Room $room)
    {
        if (!auth()->user()->canManageRoom($room)) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola ruangan ini.');
        }
    }

    public function index()
    {
        $user = auth()->user();
        $query = Room::with('photos')->orderBy('id', 'desc');

        if ($user->admin_type === 1) {
            $query->where('lantai', '!=', '19');
        } elseif ($user->admin_type === 2) {
            $query->where('lantai', '19');
        }

        $rooms = $query->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('admin.rooms.index', compact('rooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'lantai' => 'required|string|max:10',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,dipakai,maintenance',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'required|string|max:255',
        ]);

        $adminType = auth()->user()->admin_type;
        if ($adminType === 1 && (string)$validated['lantai'] === '19') {
            return redirect()->back()->withErrors(['lantai' => 'Admin 1 tidak dapat menambahkan ruangan di lantai 19.'])->withInput();
        }
        if ($adminType === 2 && (string)$validated['lantai'] !== '19') {
            return redirect()->back()->withErrors(['lantai' => 'Admin 2 hanya dapat menambahkan ruangan di lantai 19.'])->withInput();
        }

        // Clean up empty items from fasilitas array
        if (isset($validated['fasilitas'])) {
            $validated['fasilitas'] = array_values(array_filter($validated['fasilitas']));
        }

        Room::create($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $this->authorizeRoom($room);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'nullable|string|max:255',
            'lantai' => 'required|string|max:10',
            'kapasitas' => 'required|integer|min:1',
            'status' => 'required|in:tersedia,dipakai,maintenance',
            'deskripsi' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'required|string|max:255',
        ]);

        $adminType = auth()->user()->admin_type;
        if ($adminType === 1 && (string)$validated['lantai'] === '19') {
            return redirect()->back()->withErrors(['lantai' => 'Admin 1 tidak dapat memindahkan ruangan ke lantai 19.'])->withInput();
        }
        if ($adminType === 2 && (string)$validated['lantai'] !== '19') {
            return redirect()->back()->withErrors(['lantai' => 'Admin 2 hanya dapat memindahkan ruangan dari lantai 19.'])->withInput();
        }

        if (isset($validated['fasilitas'])) {
            $validated['fasilitas'] = array_values(array_filter($validated['fasilitas']));
        } else {
            $validated['fasilitas'] = [];
        }

        $room->update($validated);

        return redirect()->back()->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $this->authorizeRoom($room);

        // Delete all photo files from disk first, and delete the photo records
        foreach ($room->photos as $photo) {
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
            $photo->delete();
        }

        // Delete all reservations associated with this room first to avoid foreign key constraints
        $room->reservations()->delete();

        // Room photos will be cascade deleted on database because of cascadeOnDelete in migration
        $room->delete();

        return redirect()->back()->with('success', 'Ruangan berhasil dihapus.');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $this->authorizeRoom($room);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('rooms', 'public');

            // Find max urutan
            $maxUrutan = RoomPhoto::where('room_id', $room->id)->max('urutan') ?? 0;

            $photoObj = RoomPhoto::create([
                'room_id' => $room->id,
                'path' => $path,
                'urutan' => $maxUrutan + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diunggah.',
                'photo' => [
                    'id' => $photoObj->id,
                    'url' => asset('storage/' . $path)
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunggah foto.'
        ], 400);
    }

    public function deletePhoto($photoId)
    {
        $photo = RoomPhoto::findOrFail($photoId);
        $room = $photo->room;
        if ($room) {
            $this->authorizeRoom($room);
        }

        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus.'
        ]);
    }

    public function storeReservation(Request $request)
    {
        $tipeReservasi = $request->input('tipe_reservasi') ?: 'biasa';

        $rules = [
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:users,id',
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

        $validated = $request->validate($rules);

        $roomId = $validated['room_id'];
        $room = Room::findOrFail($roomId);
        $this->authorizeRoom($room);
        $dates = [];
        if ($tipeReservasi === 'sehari_penuh') {
            $startDate = \Illuminate\Support\Carbon::parse($validated['tanggal_mulai']);
            $endDate = \Illuminate\Support\Carbon::parse($validated['tanggal_selesai']);

            for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
                if ($d->isSunday()) {
                    continue;
                }
                $dates[] = $d->toDateString();
            }

            if (empty($dates)) {
                return redirect()->back()->withErrors(['tanggal_mulai' => 'Pemesanan ditutup untuk hari Minggu. Silakan sesuaikan rentang tanggal Anda.'])->withInput();
            }

            $jamMulai = \Illuminate\Support\Carbon::parse('07:00');
            $jamSelesai = \Illuminate\Support\Carbon::parse('18:30');
        } else {
            $dateParsed = \Illuminate\Support\Carbon::parse($validated['tanggal']);
            if ($dateParsed->isSunday()) {
                return redirect()->back()->withErrors(['tanggal' => 'Pemesanan ditutup untuk hari Minggu.'])->withInput();
            }
            $dates[] = $dateParsed->toDateString();
            $jamMulai = \Illuminate\Support\Carbon::parse($validated['jam_mulai']);
            $jamSelesai = \Illuminate\Support\Carbon::parse($validated['jam_selesai']);
        }

        if ($jamSelesai->lte($jamMulai)) {
            return redirect()->back()->withErrors(['jam_selesai' => 'Jam selesai harus setelah jam mulai.'])->withInput();
        }

        // Validate each date
        $bentrokDates = [];
        foreach ($dates as $date) {
            $mulaiReservasi = \Illuminate\Support\Carbon::parse(
                \Illuminate\Support\Carbon::parse($date)->toDateString() . ' ' . $jamMulai->format('H:i')
            );

            if ($mulaiReservasi->lte(now())) {
                $errKey = ($tipeReservasi === 'sehari_penuh') ? 'tanggal_mulai' : 'jam_mulai';
                return redirect()->back()->withErrors([$errKey => "Reservasi untuk tanggal {$date} tidak bisa diajukan karena jam mulainya sudah terlewat."])->withInput();
            }

            // Overlap check
            $overlap = \App\Models\Reservation::where('room_id', $roomId)
                ->where('tanggal', $date)
                ->whereIn('status', ['pending', 'approved'])
                ->where('jam_mulai', '<', $jamSelesai->format('H:i:00'))
                ->where('jam_selesai', '>', $jamMulai->format('H:i:00'))
                ->exists();

            if ($overlap) {
                $bentrokDates[] = \Illuminate\Support\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY');
            }
        }

        if (!empty($bentrokDates)) {
            $errKey = ($tipeReservasi === 'sehari_penuh') ? 'tanggal_mulai' : 'jam_mulai';
            return redirect()->back()->withErrors([$errKey => 'Ruangan tidak tersedia (sudah dipesan) pada hari berikut: ' . implode(', ', $bentrokDates) . '.'])->withInput();
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($roomId, $validated, $dates, $jamMulai, $jamSelesai) {
            foreach ($dates as $date) {
                \App\Models\Reservation::create([
                    'room_id' => $roomId,
                    'user_id' => $validated['user_id'],
                    'tanggal' => $date,
                    'jam_mulai' => $jamMulai->format('H:i'),
                    'jam_selesai' => $jamSelesai->format('H:i'),
                    'tujuan' => $validated['tujuan'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'status' => 'approved', // Admin booking is automatically approved
                    'approved_by' => auth()->id(),
                ]);
            }
        });

        return redirect()->back()->with('success', 'Reservasi ruangan berhasil dibuat langsung.');
    }
}
