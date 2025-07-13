<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan field "username" untuk login
        Fortify::username(fn() => 'username');

        // Kustomisasi proses authenticate
        Fortify::authenticateUsing(function (Request $request) {
            $username = $request->input('username');
            $password = $request->input('password');

            // Cek Pelanggan dulu
            $pelanggan = Pelanggan::where('username', $username)->first();
            if ($pelanggan && Hash::check($password, $pelanggan->password)) {
                session(['role' => 'pelanggan']);
                Auth::guard('pelanggan')->login($pelanggan);
                return $pelanggan;
            }

            // Cek Admin
            $admin = Admin::where('username', $username)->first();
            if ($admin && Hash::check($password, $admin->password)) {
                session(['role' => 'admin']);
                Auth::guard('admin')->login($admin);
                return $admin;
            }

            // Gagal
            return null;
        });

        // Rate limiting untuk login
        RateLimiter::for('login', function (Request $request) {
            $key = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($key);
        });

        // Rate limiting untuk two-factor (jika dipakai)
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        // View untuk form login
        Fortify::loginView(fn() => view('auth.login'));
        
        // View untuk form register
        Fortify::registerView(fn() => view('auth.register'));
    }
}
