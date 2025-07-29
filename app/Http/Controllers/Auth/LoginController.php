<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses permintaan login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Coba untuk melakukan otentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // --- PERBAIKAN UTAMA DI SINI ---
            $user = Auth::user();

            // 3. Cek role dan arahkan secara langsung
            if ($user->role === 'admin') {
                // Jika role adalah admin, langsung arahkan ke dashboard admin
                return redirect()->route('admin.dashboard');
            } else {
                // Jika role adalah voter (atau lainnya), arahkan ke halaman pemilihan
                return redirect()->route('vote.index');
            }
        }

        // 4. Jika otentikasi gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Memproses permintaan logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Arahkan ke halaman utama setelah logout
    }
}
