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
        return view('admin.Kehadiran.kehadiran-hari-ini');
    }

    public function kehadiranData()
    {
        $today = Carbon::today();

        $hadir = Absensi::with('user.kelas', 'user.jurusan')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->get();

        $izin = Absensi::with('user.kelas', 'user.jurusan')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'izin')
            ->whereHas('user', function ($q) {
                $q->where('usertype', 'siswa');
            })
            ->latest()
            ->get();

        $sakit = Absensi::with('user.kelas', 'user.jurusan')
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
            ->with(['kelas', 'jurusan'])
            ->get();

        $izinPulangRaw = Absensi::with('user.kelas', 'user.jurusan')
            ->whereDate('tanggal', $today)
            ->where('status_pulang', 'pending')
            ->whereHas('user', fn($q) => $q->where('usertype', 'siswa'))
            ->latest()
            ->get();

        $izinPulang = $izinPulangRaw->map(fn($a) => [
            'id'          => $a->id,
            'user'        => $a->user,
            'waktu'       => $a->waktu ? \Carbon\Carbon::parse($a->waktu)->format('H:i') : '-',
            'tipe_pulang' => $a->tipe_pulang ?? 'izin', // izin|sakit
            'alasan'      => $a->alasan_pulang,
            'status'      => $a->status_pulang,
        ]);

        // tambahin ke response: 'izin_pulang' => $izinPulang,

        return response()->json([
            'hadir' => $hadir,
            'izin' => $izin,
            'sakit' => $sakit,
            'belum' => $belumAbsen,
            'izin_pulang' => $izinPulang
        ]);
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $kehadiran = Absensi::findOrFail($id);
        $kehadiran->update(['status' => $request->status]);

        // Jika izin/sakit ditolak, hapus record agar siswa bisa absen ulang
        if ($request->status === 'rejected' && in_array($kehadiran->keterangan, ['izin', 'sakit'])) {
            $kehadiran->delete();
            return response()->json(['message' => 'Pengajuan ditolak. Siswa dapat melakukan absen ulang.']);
        }

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }

    public function updateApprovalPulang(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $absensi = Absensi::findOrFail($id);

        if ($request->status === 'approved') {
            $absensi->update([
                'status_pulang' => 'approved',
                'waktu_pulang'  => now()->toTimeString(),
            ]);
        } else {
            $absensi->update([
                'status_pulang' => 'rejected',
                'tipe_pulang'   => null,
                'alasan_pulang' => null,
            ]);
            // waktu_pulang sengaja dibiarkan null
        }

        return response()->json(['message' => 'Status pulang berhasil diperbarui']);
    }

    // ═══════════════════════════════════════════════════════════
    //  HALAMAN ABSEN PULANG
    // ═══════════════════════════════════════════════════════════

    public function absenPulang()
    {
        return view('admin.Kehadiran.absen-pulang');
    }

    public function absenPulangData()
    {
        $today = Carbon::today();

        // Siswa yang sudah pulang (waktu_pulang terisi)
        $sudahPulang = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->whereNotNull('waktu_pulang')
            ->whereHas('user', fn($q) => $q->where('usertype', 'siswa'))
            ->latest('waktu_pulang')
            ->get()
            ->map(fn($a) => [
                'id'           => $a->id,
                'user'         => $a->user,
                'waktu_masuk'  => $a->waktu ? Carbon::parse($a->waktu)->format('H:i') : '-',
                'waktu_pulang' => $a->waktu_pulang ? Carbon::parse($a->waktu_pulang)->format('H:i') : '-',
                'tipe_pulang'  => $a->tipe_pulang,
                'status_pulang' => $a->status_pulang,
                'foto_pulang'   => $a->foto_pulang,
            ]);

        // Siswa yang belum pulang
        $belumPulang = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->whereNull('waktu_pulang')
            ->where(function ($q) {
                $q->whereNull('status_pulang')
                    ->orWhere('status_pulang', 'rejected');
            })
            ->whereHas('user', fn($q) => $q->where('usertype', 'siswa'))
            ->latest()
            ->get()
            ->map(fn($a) => [
                'id'           => $a->id,
                'user'         => $a->user,
                'waktu_masuk'  => $a->waktu ? Carbon::parse($a->waktu)->format('H:i') : '-',
                'status_pulang' => $a->status_pulang,
            ]);

        // Pengajuan izin/sakit pulang (pending)
        $izinPulang = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->where('status_pulang', 'pending')
            ->whereHas('user', fn($q) => $q->where('usertype', 'siswa'))
            ->latest()
            ->get()
            ->map(fn($a) => [
                'id'           => $a->id,
                'user'         => $a->user,
                'waktu_masuk'  => $a->waktu ? Carbon::parse($a->waktu)->format('H:i') : '-',
                'tipe_pulang'  => $a->tipe_pulang ?? 'izin',
                'alasan'       => $a->alasan_pulang,
                'status'       => $a->status_pulang,
            ]);

        return response()->json([
            'sudah_pulang' => $sudahPulang,
            'belum_pulang' => $belumPulang,
            'izin_pulang'  => $izinPulang,
        ]);
    }
}
