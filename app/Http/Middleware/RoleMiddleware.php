<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Middleware ini sebaiknya digunakan untuk otorisasi setelah autentikasi.
        // Jika Anda menggunakan `auth:guard_name` di route, middleware `auth` bawaan Laravel
        // sudah akan menangani redirect ke login jika tidak terautentikasi.
        // Jadi, di sini kita hanya perlu memeriksa peran.



        if ($role === 'admin') {
            if (!Auth::guard('admin')->check()) {
                // Jika user bukan admin, redirect ke halaman login atau halaman tidak berwenang
                return redirect('/login'); // Atau route('unauthorized')
            }
        } elseif ($role === 'pelanggan') {
            // Pastikan user terautentikasi sebagai pelanggan dan BUKAN admin
            if (!Auth::guard('pelanggan')->check() || Auth::guard('admin')->check()) {
                return redirect('/login'); // Atau route('unauthorized')
            }
        }

        return $next($request);
    }
}
