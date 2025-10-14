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
*/

// --- Rute untuk Pengguna yang Belum Login (Guest) ---
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// --- Rute untuk Pengguna yang Sudah Login (Authenticated) ---
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // [PERBAIKAN FINAL] Alur setelah login berdasarkan role (tanpa 'voted_all')
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // 1. Jika role adalah ADMIN
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Jika role adalah PEMILIH OSIS
        // Pengguna ini akan selalu diarahkan ke halaman vote OSIS.
        // Halaman itu sendiri yang akan menampilkan pesan "sudah memilih" jika vote telah dilakukan.
        if ($user->role === 'voter_osis') {
            return redirect()->route('vote.index', 'osis'); 
        }

        // 3. Jika role adalah PEMILIH MPK
        // Sama seperti OSIS, selalu arahkan ke halaman vote MPK.
        if ($user->role === 'voter_mpk') {
            return redirect()->route('vote.index', 'mpk'); 
        }
        
        // 4. Jika role adalah 'voter' umum (bisa memilih keduanya)
        if (!$user->has_voted_osis) {
            return redirect()->route('vote.index', 'osis');
        }
        if (!$user->has_voted_mpk) {
            return redirect()->route('vote.index', 'mpk');
        }

        // Jika sudah memilih semua, arahkan saja ke salah satunya (misal OSIS).
        // Halaman vote.index akan menampilkan pesan "Anda sudah memilih".
        return redirect()->route('vote.index', 'osis');
        
    })->name('dashboard');

    // Rute Halaman Pemilihan (Dinamis)
    Route::get('/vote/{type}', [VoteController::class, 'index'])
        ->whereIn('type', ['osis', 'mpk'])
        ->name('vote.index');
    
    Route::post('/vote/{type}', [VoteController::class, 'store'])
        ->whereIn('type', ['osis', 'mpk'])
        ->name('vote.store');

    // --- Grup Rute Khusus Admin ---
    Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
        
        // Rute Dashboard Admin dengan Logika Lengkap dan Akurat
        Route::get('/dashboard', function () {
            $totalCandidates = \App\Models\OsisCandidate::count() + \App\Models\MpkCandidate::count();
            $totalVoters = \App\Models\User::whereIn('role', ['voter', 'voter_osis', 'voter_mpk'])->count();
            $usersVoted = \App\Models\User::whereIn('role', ['voter', 'voter_osis', 'voter_mpk'])
                            ->where(fn($q) => $q->where('has_voted_osis', true)->orWhere('has_voted_mpk', true))
                            ->count();
            $votePercentage = $totalVoters > 0 ? ($usersVoted / $totalVoters) * 100 : 0;
            $osisVotes = \App\Models\Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();
            $mpkVotes = \App\Models\Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();
            $totalVotes = $osisVotes + $mpkVotes;
            $osisCandidatesData = \App\Models\OsisCandidate::withCount('votes')->get();
            $mpkCandidatesData = \App\Models\MpkCandidate::withCount('votes')->get();
            $allCandidates = $osisCandidatesData->concat($mpkCandidatesData);
            $candidateNames = $allCandidates->map(fn($c) => $c->name_ketua . ($c->name_wakil ? ' & ' . $c->name_wakil : ''));
            $voteCounts = $allCandidates->pluck('votes_count');
            
            return view('admin.dashboard', compact(
                'totalCandidates', 'usersVoted', 'totalVoters', 'votePercentage',
                'osisVotes', 'mpkVotes', 'totalVotes',
                'candidateNames', 'voteCounts'
            ));
        })->name('dashboard');
        
        Route::resource('osis-candidates', OsisCandidateController::class);
        Route::resource('mpk-candidates', MpkCandidateController::class);
        Route::get('results/osis', [ResultController::class, 'osisResults'])->name('results.osis');
        Route::get('results/mpk', [ResultController::class, 'mpkResults'])->name('results.mpk');
        Route::get('results/dashboard', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/fetch', [ResultController::class, 'fetchResults'])->name('results.fetch');
        Route::get('results/report', [ResultController::class, 'generateReport'])->name('results.report');
    });
});