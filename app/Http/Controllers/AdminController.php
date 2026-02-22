<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $jmlhsiswa = User::where('usertype', 'siswa')->count();

        // Total hadir hari ini
        $totalHadir = Absensi::whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->count();

        $persenKehadiran = $jmlhsiswa > 0
            ? round(($totalHadir / $jmlhsiswa) * 100, 2)
            : 0;

        $jmlTerlambat = Absensi::whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->where('waktu', '>', '07:00:00')
            ->count();

        // 🔥 Ambil semua user_id yang sudah absen hari ini
        $sudahAbsenIds = Absensi::whereDate('tanggal', $today)
            ->pluck('user_id');

        // 🔥 Hitung siswa yang tidak ada di absensis hari ini
        $jmlBelumAbsen = User::where('usertype', 'siswa')
            ->whereNotIn('id', $sudahAbsenIds)
            ->count();

        return view('admin.dashboard', compact(
            'jmlhsiswa',
            'persenKehadiran',
            'jmlTerlambat',
            'jmlBelumAbsen'
        ));
    }
}
