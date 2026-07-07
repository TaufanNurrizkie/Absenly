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
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00') . ':00';

        // ── Stat Cards ────────────────────────────────────────────
        $jmlhsiswa = User::where('usertype', 'siswa')->count();

        $totalHadir = Absensi::whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->count();

        $persenKehadiran = $jmlhsiswa > 0
            ? round(($totalHadir / $jmlhsiswa) * 100, 1)
            : 0;

        $jmlTerlambat = Absensi::whereDate('tanggal', $today)
            ->where('keterangan', 'hadir')
            ->where('waktu', '>', $jamMasuk)
            ->count();

        $sudahAbsenIds = Absensi::whereDate('tanggal', $today)->pluck('user_id');

        $jmlBelumAbsen = User::where('usertype', 'siswa')
            ->whereNotIn('id', $sudahAbsenIds)
            ->count();

        // ── Grafik Mingguan (7 hari terakhir) ────────────────────
        $grafikLabels = [];
        $grafikData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $grafikLabels[] = $date->translatedFormat('D'); // Sen, Sel, Rab...
            $grafikData[]   = Absensi::whereDate('tanggal', $date)
                ->where('keterangan', 'hadir')
                ->count();
        }

        // ── Rekap Per Kelas ───────────────────────────────────────
        // Asumsi: User has kolom 'kelas' dan 'jurusan'
        $kelasList = User::where('usertype', 'siswa')
            ->whereNotNull('kelas')
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $rekapKelas = $kelasList->map(function ($kelas) use ($today, $jamMasuk) {
            $totalSiswa = User::where('usertype', 'siswa')
                ->where('kelas', $kelas)
                ->count();

            $absensiIds = User::where('usertype', 'siswa')
                ->where('kelas', $kelas)
                ->pluck('id');

            $hadir = Absensi::whereDate('tanggal', $today)
                ->where('keterangan', 'hadir')
                ->whereIn('user_id', $absensiIds)
                ->count();

            $terlambat = Absensi::whereDate('tanggal', $today)
                ->where('keterangan', 'hadir')
                ->where('waktu', '>', $jamMasuk)   // <-- ini $jamMasuk gak ke-capture
                ->whereIn('user_id', $absensiIds)
                ->count();

            $tidakHadir = Absensi::whereDate('tanggal', $today)
                ->whereIn('keterangan', ['izin', 'sakit'])
                ->whereIn('user_id', $absensiIds)
                ->count();

            $alfa = $totalSiswa - $hadir - $tidakHadir;

            $persen = $totalSiswa > 0
                ? round(($hadir / $totalSiswa) * 100, 1)
                : 0;

            return [
                'kelas'       => $kelas,
                'total'       => $totalSiswa,
                'hadir'       => $hadir,
                'terlambat'   => $terlambat,
                'tidak_hadir' => $tidakHadir + max($alfa, 0),
                'persen'      => $persen,
            ];
        })->sortByDesc('persen')->values();

        // ── Aktivitas Terbaru ─────────────────────────────────────
        $aktivitas = Absensi::with('user')
            ->whereDate('tanggal', $today)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(function ($absen) use ($jamMasuk) {
                $nama  = $absen->user->name  ?? 'Siswa';
                $kelas = $absen->user->kelas ?? '';
                $waktu = Carbon::parse($absen->waktu)->format('H:i') . ' WIB';

                switch ($absen->keterangan) {
                    case 'hadir':
                        $terlambat = $absen->waktu > $jamMasuk;
                        return [
                            'warna' => $terlambat ? 'orange' : 'blue',
                            'pesan' => $terlambat
                                ? "{$nama} terlambat — {$kelas}"
                                : "{$nama} hadir — {$kelas}",
                            'waktu' => $waktu,
                        ];
                    case 'izin':
                        return [
                            'warna' => 'yellow',
                            'pesan' => "{$nama} izin tidak hadir — {$kelas}",
                            'waktu' => $waktu,
                        ];
                    case 'sakit':
                        return [
                            'warna' => 'blue',
                            'pesan' => "{$nama} sakit — {$kelas}",
                            'waktu' => $waktu,
                        ];
                    default:
                        return [
                            'warna' => 'gray',
                            'pesan' => "{$nama} absen — {$kelas}",
                            'waktu' => $waktu,
                        ];
                }
            });

        return view('admin.dashboard', compact(
            'jmlhsiswa',
            'persenKehadiran',
            'jmlTerlambat',
            'jmlBelumAbsen',
            'grafikLabels',
            'grafikData',
            'rekapKelas',
            'aktivitas'
        ));
    }
}
