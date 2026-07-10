<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use App\Models\Kelas;
use App\Notifications\PointNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Kelas sekarang tabel terpisah, users punya kelas_id
        $kelasList = Kelas::whereHas('users', fn($q) => $q->where('usertype', 'siswa'))
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $rekapKelas = $kelasList->map(function ($kelasItem) use ($today, $jamMasuk) {
            $totalSiswa = User::where('usertype', 'siswa')
                ->where('kelas_id', $kelasItem->id)
                ->count();

            $absensiIds = User::where('usertype', 'siswa')
                ->where('kelas_id', $kelasItem->id)
                ->pluck('id');

            $hadir = Absensi::whereDate('tanggal', $today)
                ->where('keterangan', 'hadir')
                ->whereIn('user_id', $absensiIds)
                ->count();

            $terlambat = Absensi::whereDate('tanggal', $today)
                ->where('keterangan', 'hadir')
                ->where('waktu', '>', $jamMasuk)
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
                'kelas'       => $kelasItem->nama,
                'total'       => $totalSiswa,
                'hadir'       => $hadir,
                'terlambat'   => $terlambat,
                'tidak_hadir' => $tidakHadir + max($alfa, 0),
                'persen'      => $persen,
            ];
        })->sortByDesc('persen')->values();

        // ── Aktivitas Terbaru ─────────────────────────────────────
        $aktivitas = Absensi::with('user.kelas')
            ->whereDate('tanggal', $today)
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(function ($absen) use ($jamMasuk) {
                $nama  = $absen->user->name ?? 'Siswa';
                $kelas = $absen->user->kelas->nama ?? '';
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

    public function scan()
    {
        return view('admin.scan');
    }

    public function scanInfo(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::with('kelas')->findOrFail($request->user_id);

        if ($user->usertype !== 'siswa') {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR Code tidak valid atau bukan milik siswa.',
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'nis' => $user->nis,
                'kelas' => $user->kelas->nama ?? '-',
                'foto' => $user->foto ? asset('img/' . $user->foto) : null,
            ]
        ]);
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipe_absen' => 'required|in:hadir,pulang',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->usertype !== 'siswa') {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR Code tidak valid atau bukan milik siswa.',
            ], 400);
        }

        $now = Carbon::now();
        if ($now->isWeekend()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada absensi di hari weekend.',
            ], 400);
        }

        $todayStr = Carbon::today()->toDateString();

        if ($request->tipe_absen === 'pulang') {
            $absensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $todayStr)
                ->where('keterangan', 'hadir')
                ->first();

            if (!$absensi) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Siswa {$user->name} belum absen masuk hari ini.",
                ], 400);
            }

            if ($absensi->waktu_pulang) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Siswa {$user->name} sudah absen pulang hari ini.",
                ], 400);
            }

            $jamPulang = \App\Models\Setting::get('jam_pulang', '15:00');
            if ($now->format('H:i') < $jamPulang) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Absen pulang hanya tersedia mulai jam ' . $jamPulang . '.',
                ], 400);
            }

            $absensi->waktu_pulang = $now->toTimeString();
            $absensi->status_pulang = 'approved';
            $absensi->tipe_pulang = 'normal';
            $absensi->save();

            return response()->json([
                'status'        => 'success',
                'message'       => "Berhasil absen pulang untuk {$user->name}!",
                'user'          => $user->name,
                'points_earned' => 0,
            ]);
        }

        // --- Logika tipe_absen === 'hadir' ---
        $batasAbsen = \App\Models\Setting::get('batas_absen', '23:59');
        if ($now->format('H:i') > $batasAbsen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Absensi sudah ditutup. Maksimal sampai jam ' . $batasAbsen . '.',
            ], 400);
        }

        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $todayStr)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status'  => 'error',
                'message' => "Siswa {$user->name} sudah absen hari ini.",
            ], 400);
        }

        // Streak logic
        $lastWorkingDay = Carbon::today();
        do {
            $lastWorkingDay->subDay();
        } while ($lastWorkingDay->isWeekend());

        $lastWorkingDayStr = $lastWorkingDay->toDateString();
        $lastAbsenDate = $user->last_absen_date instanceof Carbon
            ? $user->last_absen_date->toDateString()
            : $user->last_absen_date;

        if ($lastAbsenDate === $lastWorkingDayStr) {
            $user->absen_streak += 1;
        } else {
            $user->absen_streak = 1;
        }
        $user->last_absen_date = $todayStr;

        // Save Attendance
        Absensi::create([
            'user_id'      => $user->id,
            'tanggal'      => $todayStr,
            'waktu'        => $now->toTimeString(),
            'latitude'     => null,
            'longitude'    => null,
            'lokasi_valid' => true,
            'foto'         => null,
            'keterangan'   => 'hadir',
            'status'       => 'approved',
        ]);

        // Points logic
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00');
        $isLate   = $now->format('H:i') >= $jamMasuk;
        $pointEarned = $isLate ? 5 : 10; // POINT_HADIR_TELAT : POINT_HADIR_TEPAT
        $reasons = [];

        if ($isLate) {
            $reasons[] = 'Hadir (telat) +5 poin';
            $user->late_streak += 1;
        } else {
            $reasons[] = 'Hadir tepat waktu +10 poin';
            $user->late_streak = 0;
        }

        if ($user->late_streak > 0 && $user->late_streak % 3 === 0) { // LATE_STREAK_LIMIT
            $pointEarned -= 15; // POINT_LATE_STREAK
            $reasons[] = 'Telat 3x beruntun -15 poin';
        }

        if ($user->absen_streak % 5 === 0) {
            $pointEarned += 20; // POINT_STREAK_BONUS
            $reasons[] = 'Streak ' . $user->absen_streak . ' hari +20 poin bonus';
        }

        $user->Point += $pointEarned;
        $user->save();

        $user->notify(new PointNotification(
            pointChange: $pointEarned,
            totalPoints: $user->Point,
            reason: implode(' | ', $reasons),
        ));

        return response()->json([
            'status'        => 'success',
            'message'       => "Berhasil absen untuk {$user->name}!",
            'user'          => $user->name,
            'points_earned' => $pointEarned,
        ]);
    }
}
