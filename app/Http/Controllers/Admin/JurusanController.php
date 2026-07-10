<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusanList = Jurusan::withCount('users')->latest()->get();
        return view('admin.jurusan.index', compact('jurusanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jurusans,nama',
        ]);

        Jurusan::create(['nama' => $request->nama]);

        return back()->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:jurusans,nama,' . $jurusan->id,
        ]);

        $jurusan->update(['nama' => $request->nama]);

        return back()->with('success', 'Jurusan berhasil diupdate');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus');
    }
}
