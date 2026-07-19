<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    public function indexSiswa()
    {
        $users = User::with(['kelas', 'jurusan'])->where('usertype', 'siswa')->latest()->get();
        $kelasList = Kelas::orderBy('nama')->get();
        $jurusanList = Jurusan::orderBy('nama')->get();
        return view('admin.users.indexSiswa', compact('users', 'kelasList', 'jurusanList'));
    }

    public function indexGuru()
    {
        $gurus = User::where('usertype', 'guru')->latest()->get();
        return view('admin.users.indexGuru', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'nis' => 'required|unique:users',
            'nohp' => 'required|unique:users',
            'password' => ['required', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()]
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
            'kelas_id' => $request->kelas_id,
            'jurusan_id' => $request->jurusan_id,
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
            'kelas_id' => $request->kelas_id,
            'jurusan_id' => $request->jurusan_id,
            'usertype' => $request->usertype,
            'point' => $request->point
        ]);

        if ($request->password) {
            $request->validate([
                'password' => [\Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()]
            ]);
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            if ($i === 1) continue; // skip header

            $name  = trim($row['A'] ?? '');
            $email = trim($row['B'] ?? '');
            $nis   = trim($row['C'] ?? '');

            // Skip baris kosong atau baris contoh
            if (!$name || !$email || !$nis) {
                $skipped++;
                continue;
            }

            // Skip jika email atau NIS sudah ada
            if (\App\Models\User::where('email', $email)->orWhere('nis', $nis)->exists()) {
                $errors[] = "Baris $i: email/NIS sudah terdaftar ($email / $nis)";
                $skipped++;
                continue;
            }

            try {


                User::create([
                    'name'          => $row['A'],
                    'email'         => $row['B'],
                    'nis'           => $row['C'],
                    'nohp'          => $row['D'],
                    'alamat'        => $row['E'],
                    'tempat_lahir'  => $row['F'],
                    'tanggal_lahir' => $row['G'],
                    'jenis_kelamin' => strtoupper($row['H']),
                    'foto'          => $row['I'] ?: null,
                    'kelas_id'      => $row['J'],
                    'jurusan_id'    => $row['K'],
                    'Point'         => $row['L'] ?: 100,
                    'usertype'      => $row['M'] ?: 'siswa',
                    'password'      => Hash::make($nis),
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris $i: " . $e->getMessage();
            }
        }



        $msg = "Berhasil import $imported user.";
        if ($skipped) $msg .= " $skipped dilewati.";
        if ($errors)  $msg .= " Lihat detail error di bawah.";

        return back()->with('import_result', [
            'message' => $msg,
            'errors'  => $errors,
        ]);
    }

    public function importGuru(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        foreach ($rows as $i => $row) {
            if ($i === 1) continue;

            $name  = trim($row['A'] ?? '');
            $email = trim($row['B'] ?? '');
            $nis   = trim($row['C'] ?? '');

            if (!$name || !$email || !$nis) {
                $skipped++;
                continue;
            }

            if (User::where('email', $email)->orWhere('nis', $nis)->exists()) {
                $errors[] = "Baris $i: email/nis sudah terdaftar ($email / $nis)";
                $skipped++;
                continue;
            }

            try {
                User::create([
                    'name'           => $name,
                    'email'          => $email,
                    'nis'            => $nis,
                    'nohp'           => $row['D'] ?? null,
                    'mata_pelajaran' => $row['E'] ?? null,
                    'tempat_lahir'   => $row['F'] ?? null,
                    'tanggal_lahir'  => $row['G'] ?? null,
                    'jenis_kelamin'  => $row['H'] ?? 'L',
                    'alamat'         => $row['I'] ?? null,
                    'usertype'       => 'guru',
                    'password'       => Hash::make($nis),
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris $i: " . $e->getMessage();
            }
        }

        $msg = "Berhasil import $imported guru.";
        if ($skipped) $msg .= " $skipped dilewati.";
        if ($errors)  $msg .= " Lihat detail error di bawah.";

        return back()->with('import_result', [
            'message' => $msg,
            'errors'  => $errors,
        ]);
    }
}
