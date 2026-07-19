<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\HariLibur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class GuruAbsenController extends Controller
{
    public function absen()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        $sudahAbsen = Absensi::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        $riwayatAbsensi = Absensi::where('user_id', $user->id)
            ->latest('created_at')
            ->get();

        $hariLiburHariIni = HariLibur::todayHoliday();

        return view('guru.absen', compact('user', 'sudahAbsen', 'riwayatAbsensi', 'hariLiburHariIni'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        if ($now->format('H:i') > '23:00') {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi sudah ditutup.'
            ], 400);
        }

        $today = Carbon::today();

        if ($holiday = HariLibur::todayHoliday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini adalah hari libur nasional: ' . $holiday->nama
            ], 400);
        }

        $sudahAbsen = Absensi::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah absen hari ini.'
            ], 400);
        }

        // proses foto
        $imageData = $request->photo;
        $imageName = 'absen_guru_' . time() . '.jpg';
        $imagePath = 'absen_photos/' . $imageName;

        $image = base64_decode(explode(',', $imageData)[1]);

        $manager = new ImageManager(new Driver());
        $img = $manager->read($image)
            ->scale(width: 400)
            ->toJpeg(70);

        Storage::disk('public')->put($imagePath, $img);

        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00');
        $isLate = now()->format('H:i') >= $jamMasuk;

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'waktu' => now()->toTimeString(),
            'latitude' => $request->lat,
            'longitude' => $request->lng,
            'lokasi_valid' => true,
            'foto' => $imagePath,
            'keterangan' => 'hadir',
            'status' => 'approved',
            'terlambat' => $isLate
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi guru berhasil!'
        ]);
    }
}
