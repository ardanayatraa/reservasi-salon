<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyController;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Responses\LogoutResponse;

class CustomAuthenticatedSessionController extends FortifyController
{
    public function store(LoginRequest $request)
    {
        // 1. Biarkan Fortify authenticate dan login sesuai authenticateUsing()
        $response = parent::store($request);

        // 2. Tentukan guard mana yang berhasil login
        if (Auth::guard('admin')->check()) {
            $user  = Auth::guard('admin')->user();
            $role  = 'admin';
        } elseif (Auth::guard('pelanggan')->check()) {
            $user  = Auth::guard('pelanggan')->user();
            $role  = 'pelanggan';
        } else {
            // kalau belum login di kedua guard
            return $response;
        }


        // 3. Simpan role di session
        session(['role' => $role]);

        // 4. Redirect sesuai role
        $redirectTo = match ($role) {
            'admin'     => '/dashboard',
            'pelanggan' => '/customer/dashboard',
        };

        return redirect()->intended($redirectTo);
    }

    public function destroy(Request $request): LogoutResponse
    {
        // Logout dari masing-masing guard
        Auth::guard('admin')->logout();
        Auth::guard('pelanggan')->logout();

        // Bersihkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->forget('role');

        return app(LogoutResponse::class);
    }
}
