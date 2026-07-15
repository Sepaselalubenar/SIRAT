<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Tampilkan form login admin.
     */
    public function create()
    {
        $guidePath = base_path('PANDUAN_ADMIN.md');
        $guideHtml = '';
        if (file_exists($guidePath)) {
            $guideHtml = \Illuminate\Support\Str::markdown(file_get_contents($guidePath));
        }
        return view('auth.admin-login', compact('guideHtml'));
    }

    /**
     * Login admin dengan email + password standar.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt(array_merge($credentials, ['role' => 'admin']), $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
