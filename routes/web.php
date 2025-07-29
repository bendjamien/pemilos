<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\OsisCandidateController;
use App\Http\Controllers\Admin\MpkCandidateController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\VoteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute untuk Pengguna yang Belum Login (Guest) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});


// --- Rute untuk Pengguna yang Sudah Login (Authenticated) ---
Route::middleware('auth')->group(function () {

    // Rute Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Alur untuk pemilih biasa
    Route::get('/dashboard', function () {
        // Cek dulu, jika ternyata admin, lempar ke dashboard admin
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        // Jika pemilih, arahkan ke halaman vote
        return redirect()->route('vote.index');
    })->name('dashboard');

    // Rute untuk Halaman Pemilihan
    Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VoteController::class, 'store'])->name('vote.store');


    // --- Grup Rute Khusus Admin ---
    Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
        
        Route::get('/dashboard', function () {
            // Hitung total kandidat OSIS & MPK
            $totalCandidates = \App\Models\OsisCandidate::count() + \App\Models\MpkCandidate::count();
            
            // Hitung total suara yang sudah masuk
            $totalVotes = \App\Models\Vote::count();
            
            // Hitung total pemilih (user dengan role 'voter')
            $totalVoters = \App\Models\User::where('role', 'voter')->count();

            // Hitung jumlah pemilih yang sudah memberikan suara
            $usersVoted = \App\Models\User::where('role', 'voter')->where('has_voted', true)->count();

            // Hitung persentase partisipasi
            $votePercentage = $totalVoters > 0 ? ($usersVoted / $totalVoters) * 100 : 0;
    
            // Kirim data ke view
            return view('admin.dashboard', compact('totalCandidates', 'totalVotes', 'totalVoters', 'votePercentage'));
        })->name('dashboard');

        // Rute untuk kelola kandidat
        Route::resource('osis-candidates', OsisCandidateController::class);
        Route::resource('mpk-candidates', MpkCandidateController::class);

        // Rute untuk hasil suara
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
    });
});
