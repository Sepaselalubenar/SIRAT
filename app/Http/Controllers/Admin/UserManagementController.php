<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['dosen', 'pegawai'])->withCount('reservations');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('nip', 'ilike', "%{$search}%")
                  ->orWhere('phone_number', 'ilike', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'nip'          => 'nullable|string|max:20|unique:users,nip',
            'phone_number' => 'nullable|string|max:20',
            'role'         => 'required|string|in:dosen,pegawai',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'nip.unique'     => 'NIP sudah terdaftar.',
            'role.required'  => 'Role wajib dipilih.',
            'role.in'        => 'Role tidak valid.',
        ]);

        User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'nip'          => $validated['nip'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'role'         => $validated['role'],
        ]);

        return redirect()->back()->with('success', 'Akun ' . ucfirst($validated['role']) . ' berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'nip'          => 'nullable|string|max:20|unique:users,nip,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'role'         => 'required|string|in:dosen,pegawai',
        ], [
            'name.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique'   => 'Email sudah digunakan.',
            'nip.unique'     => 'NIP sudah terdaftar.',
            'role.required'  => 'Role wajib dipilih.',
            'role.in'        => 'Role tidak valid.',
        ]);

        $user->update([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'nip'          => $validated['nip'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'role'         => $validated['role'],
        ]);

        return redirect()->back()->with('success', 'Data ' . ucfirst($user->role) . ' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        $roleName = ucfirst($user->role);

        // Delete all associated reservations first
        $user->reservations()->delete();
        $user->delete();

        return redirect()->back()->with('success', 'Akun ' . $roleName . ' berhasil dihapus.');
    }
}
