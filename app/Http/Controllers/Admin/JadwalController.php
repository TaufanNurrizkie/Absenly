<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::latest()->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:100',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('gambar')->store('jadwal', 'public');

        Jadwal::create([
            'judul'  => $request->judul,
            'gambar' => $path,
        ]);

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'judul'  => 'required|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $jadwal->judul = $request->judul;

        if ($request->hasFile('gambar')) {
            Storage::disk('public')->delete($jadwal->gambar);
            $jadwal->gambar = $request->file('gambar')->store('jadwal', 'public');
        }

        $jadwal->save();

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Jadwal $jadwal)
    {
        Storage::disk('public')->delete($jadwal->gambar);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
                         ->with('success', 'Jadwal berhasil dihapus!');
    }
}