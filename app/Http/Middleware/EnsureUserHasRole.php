<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Batasi akses route berdasarkan role user yang sedang login.
     *
     * Pemakaian di routes: ->middleware('role:admin') atau ->middleware('role:dosen')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();
        $loginRoute = $role === 'admin' ? 'admin.login' : 'login';

        if (! $user) {
            return redirect()->route($loginRoute);
        }

        if ($user->role !== $role) {
            abort(403, 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
