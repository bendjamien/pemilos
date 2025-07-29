<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OsisCandidate;
use App\Models\MpkCandidate;
use App\Models\Vote;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        // --- Menghitung Hasil OSIS ---
        // Mengambil semua kandidat OSIS beserta jumlah suaranya menggunakan withCount()
        $osisCandidates = OsisCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        // Menghitung total suara yang masuk khusus untuk OSIS
        $totalOsisVotes = Vote::where('candidate_type', 'App\Models\OsisCandidate')->count();

        // --- Menghitung Hasil MPK ---
        // Mengambil semua kandidat MPK beserta jumlah suaranya
        $mpkCandidates = MpkCandidate::withCount('votes')->orderBy('votes_count', 'desc')->get();
        // Menghitung total suara yang masuk khusus untuk MPK
        $totalMpkVotes = Vote::where('candidate_type', 'App\Models\MpkCandidate')->count();

        // Mengirim semua data yang dibutuhkan ke view
        return view('admin.results.index', compact(
            'osisCandidates', 
            'totalOsisVotes',
            'mpkCandidates',
            'totalMpkVotes'
        ));
    }
}
