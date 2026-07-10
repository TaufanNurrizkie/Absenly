<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::withCount('users')->latest()->get();
        return view('admin.kelas.index', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama',
        ]);

        Kelas::create(['nama' => $request->nama]);

        return back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama,' . $kela->id,
        ]);

        $kela->update(['nama' => $request->nama]);

        return back()->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus');
    }
}
