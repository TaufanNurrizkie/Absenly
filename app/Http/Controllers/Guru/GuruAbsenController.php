<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
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
        return view('guru.absen');
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

        Absensi::create([
            'user_id' => $user->id,
            'tanggal' => $today,
            'waktu' => now()->toTimeString(),
            'latitude' => $request->lat,
            'longitude' => $request->lng,
            'lokasi_valid' => true,
            'foto' => $imagePath,
            'keterangan' => 'hadir',
            'status' => 'approved'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Absensi guru berhasil!'
        ]);
    }
}
