<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $jmlhsiswa = User::where('usertype', 'siswa')->count();
        $persenKehadiran = Absensi::where('keterangan', 'hadir')->count() / Absensi::count() * 100;
        $jmlTerlambat = Absensi::where('keterangan', 'hadir')
            ->where('waktu', '>', '07:00:00')
            ->count();

        $jmlTidakHadir = Absensi::where('keterangan', 'tidak hadir')->count();

        return view('admin.dashboard', compact('jmlhsiswa', 'persenKehadiran', 'jmlTerlambat', 'jmlTidakHadir'));
    }
}
