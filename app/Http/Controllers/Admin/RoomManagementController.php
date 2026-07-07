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
        return view('admin.rooms.index', compact('rooms'));
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

        // Delete all photo files from disk first
        foreach ($room->photos as $photo) {
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        }

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
}
