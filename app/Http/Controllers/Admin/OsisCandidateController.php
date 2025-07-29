<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OsisCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OsisCandidateController extends Controller
{
    public function index()
    {
        $candidates = OsisCandidate::latest()->get();
        return view('admin.osis.index', compact('candidates'));
    }

    public function create()
    {
        return view('admin.osis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ketua' => 'required|string|max:255',
            'name_wakil' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo_ketua' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_wakil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Proses upload foto ketua
        $photoKetuaPath = $request->file('photo_ketua')->store('photos_osis', 'public');
        
        // Proses upload foto wakil
        $photoWakilPath = $request->file('photo_wakil')->store('photos_osis', 'public');

        OsisCandidate::create([
            'name_ketua' => $request->name_ketua,
            'name_wakil' => $request->name_wakil,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'photo_ketua' => $photoKetuaPath,
            'photo_wakil' => $photoWakilPath,
        ]);

        return redirect()->route('admin.osis-candidates.index')->with('success', 'Kandidat OSIS berhasil ditambahkan.');
    }

    public function edit(OsisCandidate $osisCandidate)
    {
        return view('admin.osis.edit', compact('osisCandidate'));
    }

    public function update(Request $request, OsisCandidate $osisCandidate)
    {
        $request->validate([
            'name_ketua' => 'required|string|max:255',
            'name_wakil' => 'required|string|max:255',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'photo_ketua' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_wakil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name_ketua', 'name_wakil', 'vision', 'mission']);

        // Cek dan proses jika ada foto ketua baru
        if ($request->hasFile('photo_ketua')) {
            Storage::disk('public')->delete($osisCandidate->photo_ketua); // Hapus foto lama
            $data['photo_ketua'] = $request->file('photo_ketua')->store('photos_osis', 'public');
        }

        // Cek dan proses jika ada foto wakil baru
        if ($request->hasFile('photo_wakil')) {
            Storage::disk('public')->delete($osisCandidate->photo_wakil); // Hapus foto lama
            $data['photo_wakil'] = $request->file('photo_wakil')->store('photos_osis', 'public');
        }

        $osisCandidate->update($data);

        return redirect()->route('admin.osis-candidates.index')->with('success', 'Data kandidat OSIS berhasil diperbarui.');
    }

    public function destroy(OsisCandidate $osisCandidate)
    {
        // Hapus foto dari storage
        Storage::disk('public')->delete($osisCandidate->photo_ketua);
        Storage::disk('public')->delete($osisCandidate->photo_wakil);

        // Hapus data dari database
        $osisCandidate->delete();

        return redirect()->route('admin.osis-candidates.index')->with('success', 'Kandidat OSIS berhasil dihapus.');
    }
}
