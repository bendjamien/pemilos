<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OsisCandidate;
use App\Models\MpkCandidate;
use App\Models\Vote;

class ResultController extends Controller
{
    /**
     * Method untuk menampilkan dashboard Real Count gabungan.
     */
    public function index()
    {
        // Data ini akan di-fetch oleh JavaScript, jadi view-nya hanya kerangka.
        // Namun, kita bisa kirim data awal untuk render pertama kali jika diperlukan.
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        return view('admin.results.index', compact(
            'osisCandidates', 
            'totalOsisVotes',
            'mpkCandidates',
            'totalMpkVotes'
        ));
    }
    
    /**
     * Menampilkan halaman hasil khusus untuk OSIS.
     */
    public function osisResults()
    {
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();
        
        // Buat view baru 'admin.results.osis' untuk ini
        return view('admin.results.osis', compact('osisCandidates', 'totalOsisVotes'));
    }

    /**
     * Menampilkan halaman hasil khusus untuk MPK.
     */
    public function mpkResults()
    {
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        // Buat view baru 'admin.results.mpk' untuk ini
        return view('admin.results.mpk', compact('mpkCandidates', 'totalMpkVotes'));
    }

    /**
     * Menyediakan data JSON untuk real-time update. (Tidak perlu diubah)
     */
    public function fetchResults()
    {
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        return response()->json([
            'osisCandidates' => $osisCandidates,
            'totalOsisVotes' => $totalOsisVotes,
            'mpkCandidates' => $mpkCandidates,
            'totalMpkVotes' => $totalMpkVotes,
        ]);
    }

    /**
     * Membuat laporan final gabungan. (Tidak perlu diubah)
     */
    public function generateReport()
    {
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalOsisVotes = $osisCandidates->sum('votes_count');
        $osisWinner = $osisCandidates->first();
        
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        $totalMpkVotes = $mpkCandidates->sum('votes_count');
        $mpkWinner = $mpkCandidates->first();
        
        $totalVoters = User::where('role', 'voter')->count();
        // Perbarui cara menghitung partisipasi jika diperlukan
        $usersVotedOsis = User::where('role', 'voter')->where('has_voted_osis', true)->count();
        $usersVotedMpk = User::where('role', 'voter')->where('has_voted_mpk', true)->count();

        return view('admin.results.report', compact(
            'osisCandidates', 'totalOsisVotes', 'osisWinner',
            'mpkCandidates', 'totalMpkVotes', 'mpkWinner',
            'totalVoters', 'usersVotedOsis', 'usersVotedMpk'
        ));
    }
}