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
     * Menampilkan halaman pemilihan berdasarkan tipe (osis atau mpk).
     */
    public function index($type)
    {
        if (!in_array($type, ['osis', 'mpk'])) {
            abort(404);
        }

        $candidates = ($type === 'osis') 
            ? OsisCandidate::all() 
            : MpkCandidate::all();

        return view('vote.index', compact('candidates', 'type'));
    }

    /**
     * Menyimpan suara dan mengarahkan kembali ke halaman yang sama.
     */
    public function store(Request $request, $type)
    {
        $user = Auth::user();
        
        if (!in_array($type, ['osis', 'mpk'])) {
            return response()->json(['message' => 'Tipe pemilihan tidak valid.'], 400);
        }

        // [UBAH UNTUK TESTING] Blok 'if' di bawah ini dinonaktifkan untuk mengizinkan vote berulang.
        // Hapus komentar '/*' dan '*/' untuk mengaktifkan kembali pembatasan satu suara.
        /* if (($type === 'osis' && $user->has_voted_osis) || ($type === 'mpk' && $user->has_voted_mpk)) {
            return response()->json(['message' => 'Anda sudah memberikan suara untuk pemilihan ini.'], 403);
        }
        */

        if ($type === 'osis') {
            $request->validate(['candidate_id' => 'required|exists:osis_candidates,id']);
            $candidateTypeModel = 'App\Models\OsisCandidate';
        } else {
            $request->validate(['candidate_id' => 'required|exists:mpk_candidates,id']);
            $candidateTypeModel = 'App\Models\MpkCandidate';
        }

        try {
            DB::transaction(function () use ($request, $user, $type, $candidateTypeModel) {
                Vote::create([ 'user_id' => $user->id, 'candidate_id' => $request->candidate_id, 'candidate_type' => $candidateTypeModel, ]);
                
                // Meskipun bisa vote berulang, status 'has_voted' tetap di-update agar alur redirect berfungsi jika diperlukan.
                if ($type === 'osis') $user->has_voted_osis = true;
                else $user->has_voted_mpk = true;
                $user->save();
            });

            // Arahkan kembali ke halaman ini sendiri setelah vote berhasil
            $next_url = route('vote.index', $type);

            return response()->json([
                'message' => 'Suara Anda berhasil direkam! Terima kasih.',
                'next_url' => $next_url
            ]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan, silakan coba lagi.'], 500);
        }
    }
}
