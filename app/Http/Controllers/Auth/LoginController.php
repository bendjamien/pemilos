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

            $user = Auth::user();

            // ===================================================================
            // [PERBAIKAN UTAMA] Logika pengalihan (redirect) berdasarkan role
            // ===================================================================

            // 3. Cek role dan arahkan secara langsung dengan parameter yang benar
            switch ($user->role) {
                case 'admin':
                    // Jika admin, arahkan ke dashboard admin
                    return redirect()->route('admin.dashboard');

                case 'voter_osis':
                    // Jika hanya pemilih OSIS, arahkan langsung ke vote OSIS
                    return redirect()->route('vote.index', 'osis');

                case 'voter_mpk':
                    // Jika hanya pemilih MPK, arahkan langsung ke vote MPK
                    return redirect()->route('vote.index', 'mpk');
                
                default:
                    // Untuk role 'voter' umum atau role lainnya,
                    // periksa mana yang belum dipilih.
                    if (!$user->has_voted_osis) {
                        return redirect()->route('vote.index', 'osis');
                    }
                    if (!$user->has_voted_mpk) {
                        return redirect()->route('vote.index', 'mpk');
                    }
                    // Jika sudah semua, arahkan saja ke halaman OSIS (yang akan menampilkan pesan "sudah memilih")
                    return redirect()->route('vote.index', 'osis');
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
