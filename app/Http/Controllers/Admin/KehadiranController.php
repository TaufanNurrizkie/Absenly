<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


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
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->get();

        $izin = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'izin')
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->latest()
            ->get();

        $sakit = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'sakit')
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->get();

        $sudahAbsenUserIds = Absensi::whereDate('tanggal', $today)
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->pluck('user_id');

        $belumAbsen = User::where('usertype', 'siswa')
            ->whereNotIn('id', $sudahAbsenUserIds)
            ->get();

        return response()->json([
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'belum' => $belumAbsen
        ]);
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $kehadiran = Absensi::findOrFail($id);
        $kehadiran->update(['status' => $request->status]);
        return response()->json(['message' => 'Status berhasil diperbarui']);
    }
}
