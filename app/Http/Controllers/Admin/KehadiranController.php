<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;


class KehadiranController extends Controller
{
    public function kehadiranHariIni()
{
    return view('admin.kehadiran.kehadiran-hari-ini');
}

public function kehadiranData()
{
    $today = Carbon::today();

    $hadir = Absensi::with('user')
        ->whereDate('tanggal', $today)
        ->where('keterangan', 'hadir')
        ->get();

    $izin = Absensi::with('user')
        ->whereDate('tanggal', $today)
        ->where('keterangan', 'izin')
        ->get();

    $sakit = Absensi::with('user')
        ->whereDate('tanggal', $today)
        ->where('keterangan', 'sakit')
        ->get();

    $sudahAbsenUserIds = Absensi::whereDate('tanggal', $today)
        ->pluck('user_id');

    $belumAbsen = User::where('usertype','siswa')
        ->whereNotIn('id', $sudahAbsenUserIds)
        ->get();

    return response()->json([
        'hadir' => $hadir,
        'izin' => $izin,
        'sakit' => $sakit,
        'belum' => $belumAbsen
    ]);
}
}
