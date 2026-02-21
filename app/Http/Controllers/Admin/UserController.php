<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function indexSiswa()
    {
        $users = User::where('usertype', 'siswa')->latest()->get();
        return view('admin.users.indexSiswa', compact('users'));
    }

    public function indexGuru()
    {
        $users = User::where('usertype', 'guru')->latest()->get();
        return view('admin.users.indexGuru', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'nis' => 'required|unique:users',
            'nohp' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'nohp' => $request->nohp,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'usertype' => $request->usertype,
            'point' => $request->point ?? 0,
            'foto' => $fotoPath,
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $fotoPath = $user->foto;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('users', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nis' => $request->nis,
            'nohp' => $request->nohp,
            'alamat' => $request->alamat,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'usertype' => $request->usertype,
            'point' => $request->point
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User deleted successfully');
    }
}
