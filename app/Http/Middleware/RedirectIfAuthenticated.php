<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            # Check if guard is authenticated
            if (Auth::guard($guard)->check()) {

                # Super Admin
                if ($guard === 'karyawan' && Route::is('karyawan.*')) {
                    return redirect()->route('karyawan.dashboard');
                }

                # Admin
                elseif ($guard === 'mahasiswa' && Route::is('mahasiswa.*')) {
                    return redirect()->route('mahasiswa.dashboard');
                }

                # Normal User
                else {
                    return redirect()->route('/');
                }
            }
        }

        return $next($request);
    }
}
