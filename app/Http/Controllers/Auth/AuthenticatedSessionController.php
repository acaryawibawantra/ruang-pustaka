<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function createStaff(): View
    {
        return view('auth.staff-login');
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->authenticate($request, User::ROLE_MEMBER);
    }

    public function storeStaff(Request $request): RedirectResponse
    {
        return $this->authenticate($request, User::ROLE_ADMIN);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $wasStaff = $request->user()?->role === User::ROLE_ADMIN;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($wasStaff ? 'login.staff' : 'login');
    }

    /**
     * Authenticate a user against the credentials form of a single portal.
     */
    private function authenticate(Request $request, string $role): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials + ['is_active' => true], $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak valid.'])
                ->onlyInput('email');
        }

        if (Auth::user()->role !== $role) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => $role === User::ROLE_ADMIN
                    ? 'Akun ini bukan akun petugas. Silakan masuk melalui halaman mahasiswa.'
                    : 'Akun ini terdaftar sebagai petugas. Silakan masuk melalui halaman petugas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route($role === User::ROLE_ADMIN ? 'admin.dashboard' : 'dashboard');
    }
}
