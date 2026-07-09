<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomManagementController extends Controller
{
    public function index()
    {
        $rooms = Room::with('photos')->orderBy('id', 'desc')->get();
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
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'tujuan' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:200',
        ]);

        $roomId = $validated['room_id'];
        $tanggal = $validated['tanggal'];
        $jamMulai = \Illuminate\Support\Carbon::parse($validated['jam_mulai']);
        $jamSelesai = \Illuminate\Support\Carbon::parse($validated['jam_selesai']);

        if ($jamSelesai->lte($jamMulai)) {
            return redirect()->back()->withErrors(['jam_selesai' => 'Jam selesai harus setelah jam mulai.'])->withInput();
        }

        $mulaiReservasi = \Illuminate\Support\Carbon::parse(
            \Illuminate\Support\Carbon::parse($tanggal)->toDateString() . ' ' . $jamMulai->format('H:i')
        );

        if ($mulaiReservasi->lte(now())) {
            return redirect()->back()->withErrors(['jam_mulai' => 'Jam mulai reservasi sudah lewat. Untuk reservasi hari ini, pilih jam setelah waktu sekarang.'])->withInput();
        }

        // Overlap check
        $overlap = \App\Models\Reservation::where('room_id', $roomId)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'approved'])
            ->where('jam_mulai', '<', $jamSelesai->format('H:i'))
            ->where('jam_selesai', '>', $jamMulai->format('H:i'))
            ->exists();

        if ($overlap) {
            return redirect()->back()->withErrors(['jam_mulai' => 'Ruangan ini sudah dipesan pada rentang jam tersebut oleh reservasi lain yang aktif.'])->withInput();
        }

        \App\Models\Reservation::create([
            'room_id' => $roomId,
            'user_id' => $validated['user_id'],
            'tanggal' => $tanggal,
            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamSelesai->format('H:i'),
            'tujuan' => $validated['tujuan'],
            'keterangan' => $validated['keterangan'] ?? null,
            'status' => 'approved', // Admin booking is automatically approved
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Reservasi ruangan berhasil dibuat langsung.');
    }
}
