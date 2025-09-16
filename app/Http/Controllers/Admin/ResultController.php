<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// [PERBAIKAN] Tambahkan baris ini untuk memberitahu controller di mana menemukan Model User
use App\Models\User; 

// Pastikan model-model ini juga sudah ada dan path-nya benar
use App\Models\OsisCandidate;
use App\Models\MpkCandidate;
use App\Models\Vote;
use PDF; // Jika Anda berencana menggunakan fitur PDF

class ResultController extends Controller
{
    /**
     * Method untuk menampilkan halaman hasil (view).
     */
    public function index()
    {
        // --- Menghitung Hasil OSIS ---
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();

        // --- Menghitung Hasil MPK ---
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        // Mengirim semua data yang dibutuhkan ke view
        return view('admin.results.index', compact(
            'osisCandidates', 
            'totalOsisVotes',
            'mpkCandidates',
            'totalMpkVotes'
        ));
    }

    /**
     * Method untuk menyediakan data JSON untuk real-time update.
     */
    public function fetchResults()
    {
        // --- Menghitung Hasil OSIS ---
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();

        // --- Menghitung Hasil MPK ---
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        // Mengirim data sebagai respons JSON
        return response()->json([
            'osisCandidates' => $osisCandidates,
            'totalOsisVotes' => $totalOsisVotes,
            'mpkCandidates' => $mpkCandidates,
            'totalMpkVotes' => $totalMpkVotes,
        ]);
    }

    /**
     * Method untuk menyiapkan dan menampilkan halaman laporan final.
     */
    public function generateReport()
    {
        // === Data OSIS ===
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = $osisCandidates->sum('votes_count');
        $osisWinner = $osisCandidates->first();
        $osisLoser = $osisCandidates->count() > 1 ? $osisCandidates->last() : null;

        // === Data MPK ===
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = $mpkCandidates->sum('votes_count');
        $mpkWinner = $mpkCandidates->first();
        $mpkLoser = $mpkCandidates->count() > 1 ? $mpkCandidates->last() : null;

        // === Data Statistik Tambahan (INI BAGIAN YANG MENYEBABKAN ERROR SEBELUMNYA) ===
        $totalVoters = User::where('role', 'voter')->count();
        $usersVoted = User::where('role', 'voter')->where('has_voted', true)->count();
        $participationPercentage = $totalVoters > 0 ? ($usersVoted / $totalVoters) * 100 : 0;

        // Kirim semua data ke view laporan
        return view('admin.results.report', compact(
            'osisCandidates', 'totalOsisVotes', 'osisWinner', 'osisLoser',
            'mpkCandidates', 'totalMpkVotes', 'mpkWinner', 'mpkLoser',
            'totalVoters', 'usersVoted', 'participationPercentage'
        ));
    }
}