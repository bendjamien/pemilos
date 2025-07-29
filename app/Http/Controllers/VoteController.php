<?php

namespace App\Http\Controllers;

use App\Models\OsisCandidate;
use App\Models\MpkCandidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Menampilkan halaman pemilihan.
     */
    public function index()
    {
        // Cek apakah user sudah memilih
        if (Auth::user()->has_voted) {
            return view('vote.voted'); // Tampilkan halaman "sudah memilih"
        }

        $osisCandidates = OsisCandidate::all();
        $mpkCandidates = MpkCandidate::all();

        return view('vote.index', compact('osisCandidates', 'mpkCandidates'));
    }

    /**
     * Menyimpan suara dari pemilih.
     */
    public function store(Request $request)
    {
        // Cek lagi untuk mencegah double voting
        if (Auth::user()->has_voted) {
            return response()->json(['message' => 'Anda sudah memberikan suara.'], 403);
        }

        $request->validate([
            'osis_candidate_id' => 'required|exists:osis_candidates,id',
            'mpk_candidate_id' => 'required|exists:mpk_candidates,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Simpan suara untuk OSIS
                Vote::create([
                    'user_id' => Auth::id(),
                    'candidate_id' => $request->osis_candidate_id,
                    'candidate_type' => 'App\Models\OsisCandidate',
                ]);

                // Simpan suara untuk MPK
                Vote::create([
                    'user_id' => Auth::id(),
                    'candidate_id' => $request->mpk_candidate_id,
                    'candidate_type' => 'App\Models\MpkCandidate',
                ]);

                // Update status pemilih
                $user = Auth::user();
                $user->has_voted = true;
                // $user->save();
            });

            return response()->json(['message' => 'Suara Anda berhasil direkam! Terima kasih.']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }
}