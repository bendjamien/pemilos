<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OsisCandidate;
use App\Models\MpkCandidate;
use App\Models\Vote;
use Illuminate\Http\Request;

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
        return view('admin.results.index', compact( // Pastikan nama view Anda benar, misal 'admin.results' atau 'admin.results.index'
            'osisCandidates', 
            'totalOsisVotes',
            'mpkCandidates',
            'totalMpkVotes'
        ));
    }

    /**
     * Method BARU untuk menyediakan data JSON untuk real-time update.
     */
    public function fetchResults()
    {
        // --- Menghitung Hasil OSIS (logika yang sama dengan index) ---
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();

        // --- Menghitung Hasil MPK (logika yang sama dengan index) ---
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
}