<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Cek role di session, redirect jika tidak cocok.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  (admin|pelanggan)
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $current = session('role');

        if ($current !== $role) {
            // Redirect ke dashboard sesuai role yang sedang login
            $redirectTo = match ($current) {
                'admin'     => '/dashboard',
                'pelanggan' => '/',
                default     => '/',
            };
            return redirect($redirectTo);
        }

        return $next($request);
    }
}
