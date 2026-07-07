<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'dosen')->withCount('reservations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('nip', 'ilike', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'nip'      => 'nullable|string|max:20|unique:users,nip',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama dosen wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan.',
            'nip.unique'        => 'NIP sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'nip'      => $validated['nip'] ?? null,
            'role'     => 'dosen',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'nip'      => 'nullable|string|max:20|unique:users,nip,' . $user->id,
            'password' => 'nullable|string|min:6',
        ], [
            'name.required'  => 'Nama dosen wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'nip.unique'     => 'NIP sudah terdaftar.',
            'password.min'   => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'nip'   => $validated['nip'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Delete all associated reservations first
        $user->reservations()->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Akun dosen berhasil dihapus.');
    }
}
