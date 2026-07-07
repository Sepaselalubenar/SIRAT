<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenLoginController extends Controller
{
    /**
     * Tampilkan form login dosen.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Login dosen cukup dengan NIP + email yang cocok dengan data dosen
     * yang sudah diinput admin sebelumnya (tidak pakai password).
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'nip' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = User::where('role', 'dosen')
            ->where('nip', $credentials['nip'])
            ->where('email', $credentials['email'])
            ->first();

        if (! $user) {
            return back()
                ->withInput($request->only('nip', 'email'))
                ->withErrors(['nip' => 'NIP dan email tidak ditemukan atau tidak cocok. Hubungi CS TULT jika Anda merasa ini kesalahan.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
