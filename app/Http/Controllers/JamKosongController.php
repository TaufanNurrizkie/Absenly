<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\HariLibur;
use App\Notifications\JamkosNotification;
use Illuminate\Http\Request;

class JamKosongController extends Controller
{
    /**
     * Kirim laporan jam kosong
     */
    public function kirim(Request $request)
    {
        $siswa = $request->user();

        if (!$siswa) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $jamMasuk = Setting::get('jam_masuk', '07:00');
        $jamPulang = Setting::get('jam_pulang', '15:00');
        $now = now();
        $currentTime = $now->format('H:i');
        $isHoliday = $now->isWeekend() || HariLibur::todayHoliday();

        if ($isHoliday) {
            return response()->json(['message' => 'Hari libur, tidak bisa kirim laporan jam kosong.'], 403);
        }

        if ($currentTime < $jamMasuk || $currentTime > $jamPulang) {
            return response()->json(['message' => 'Di luar jam sekolah, tidak bisa kirim laporan jam kosong.'], 403);
        }

        try {
            User::where('usertype', 'admin')->each(function ($admin) use ($siswa) {
                $admin->notify(new JamkosNotification(
                    siswa_nama: $siswa->name ?? 'Unknown',
                    kelas: $siswa->kelas->nama ?? '-',
                    jurusan: $siswa->jurusan->nama ?? '-',
                ));
            });

            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
