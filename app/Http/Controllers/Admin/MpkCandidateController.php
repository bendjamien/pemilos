<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MpkCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MpkCandidateController extends Controller
{
    public function index()
    {
        $candidates = MpkCandidate::latest()->get();
        return view('admin.mpk.index', compact('candidates'));
    }

    public function create()
    {
        return view('admin.mpk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ketua' => 'required|string|max:255',
            'name_wakil' => 'nullable|string|max:255', // Boleh kosong
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo_ketua' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_wakil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Boleh kosong
        ]);

        $data = $request->only(['name_ketua', 'name_wakil', 'vision', 'mission']);
        
        $data['photo_ketua'] = $request->file('photo_ketua')->store('photos_mpk', 'public');
        
        if ($request->hasFile('photo_wakil')) {
            $data['photo_wakil'] = $request->file('photo_wakil')->store('photos_mpk', 'public');
        }

        MpkCandidate::create($data);

        return redirect()->route('admin.mpk-candidates.index')->with('success', 'Kandidat MPK berhasil ditambahkan.');
    }

    public function edit(MpkCandidate $mpkCandidate)
    {
        return view('admin.mpk.edit', compact('mpkCandidate'));
    }

    public function update(Request $request, MpkCandidate $mpkCandidate)
    {
        $request->validate([
            'name_ketua' => 'required|string|max:255',
            'name_wakil' => 'nullable|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo_ketua' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_wakil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name_ketua', 'name_wakil', 'vision', 'mission']);

        if ($request->hasFile('photo_ketua')) {
            Storage::disk('public')->delete($mpkCandidate->photo_ketua);
            $data['photo_ketua'] = $request->file('photo_ketua')->store('photos_mpk', 'public');
        }

        if ($request->hasFile('photo_wakil')) {
            Storage::disk('public')->delete($mpkCandidate->photo_wakil);
            $data['photo_wakil'] = $request->file('photo_wakil')->store('photos_mpk', 'public');
        }

        $mpkCandidate->update($data);

        return redirect()->route('admin.mpk-candidates.index')->with('success', 'Data kandidat MPK berhasil diperbarui.');
    }

    public function destroy(MpkCandidate $mpkCandidate)
    {
        Storage::disk('public')->delete($mpkCandidate->photo_ketua);
        if($mpkCandidate->photo_wakil) {
            Storage::disk('public')->delete($mpkCandidate->photo_wakil);
        }
        $mpkCandidate->delete();

        return redirect()->route('admin.mpk-candidates.index')->with('success', 'Kandidat MPK berhasil dihapus.');
    }
}
