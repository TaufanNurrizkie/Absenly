<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Berita;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Log;

class SiswaController extends Controller
{

    public function dashboard()
    {
        $user = \App\Models\User::find(Auth::id());
        $absensis = Absensi::where('user_id', $user->id)
            ->whereIn('keterangan', ['hadir', 'izin', 'sakit', 'alpha'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // ── Hitung hari sekolah aktif (Senin–Jumat) sampai hari ini ──
        $startOfMonth = now()->startOfMonth();
        $today        = now();

        $hariSekolah = 0;
        $current = $startOfMonth->copy();
        while ($current->lte($today)) {
            if ($current->isWeekday()) {
                $hariSekolah++;
            }
            $current->addDay();
        }

        // ── Absensi bulan ini ──
        $absenBulanIni = Absensi::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $hadir = $absenBulanIni->filter(fn($a) => strtolower($a->keterangan) === 'hadir')->count();
        $izin  = $absenBulanIni->filter(fn($a) => strtolower($a->keterangan) === 'izin')->count();
        $sakit = $absenBulanIni->filter(fn($a) => strtolower($a->keterangan) === 'sakit')->count();

        $sudahAbsen = $absenBulanIni
            ->groupBy(fn($a) => \Carbon\Carbon::parse($a->created_at)->toDateString())
            ->count();
        $alpha = max(0, $hariSekolah - $sudahAbsen);

        $statsKehadiran = [
            'hadir'       => $hadir,
            'izin'        => $izin,
            'sakit'       => $sakit,
            'alpha'       => $alpha,
            'hariSekolah' => $hariSekolah,
        ];

        // 4 berita terbaru
        $beritaDashboard = Berita::latest()->take(4)->get();

        // ── Jadwal terbaru ──
        $jadwals = Jadwal::latest()->get();

        return view('siswa.dashboard', compact(
            'user',
            'absensis',
            'beritaDashboard',
            'statsKehadiran',
            'jadwals'
        ));
    }

    public function store(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());
        $now = Carbon::now();

        // Batasi absensi hanya di hari kerja
        if ($now->isWeekend()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada absensi di hari weekend.'
            ], 400);
        }

        // Batasi absensi maksimal jam 15:00
        if ($now->format('H:i') > '23:00') {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi sudah ditutup. Maksimal sampai jam 15:00.'
            ], 400);
        }

        $today = Carbon::today();
        $todayStr = $today->toDateString();

        // 1. Cek apakah user sudah absen hari ini
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $user->id)
            ->where('created_at', $todayStr)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu sudah absen hari ini.'
            ], 400);
        }

        // 2. Cek streak - skip weekend
        $lastWorkingDay = Carbon::today();

        do {
            $lastWorkingDay->subDay();
        } while ($lastWorkingDay->isWeekend());

        $lastWorkingDayStr = $lastWorkingDay->toDateString();

        // Normalize last_absen_date ke string
        $lastAbsenDate = $user->last_absen_date instanceof \Carbon\Carbon
            ? $user->last_absen_date->toDateString()
            : $user->last_absen_date;

        if ($lastAbsenDate === $lastWorkingDayStr) {
            $user->absen_streak += 1;
        } else {
            $user->absen_streak = 1;
        }

        $user->last_absen_date = $todayStr;
        $user->save();

        // 3. Proses foto
        $imageData = $request->photo;
        $imageName = 'absen_' . time() . '.jpg';
        $imagePath = 'absen_photos/' . $imageName;

        $image = base64_decode(explode(',', $imageData)[1]);

        $manager = new ImageManager(new Driver());
        $img = $manager->read($image)
            ->scale(width: 400)
            ->toJpeg(70);

        Storage::disk('public')->put($imagePath, $img);

        // 4. Simpan ke database
        Absensi::create([
            'user_id'      => $user->id,
            'tanggal'      => $todayStr,
            'waktu'        => now()->toTimeString(),
            'latitude'     => $request->lat,
            'longitude'    => $request->lng,
            'lokasi_valid' => true,
            'foto'         => $imagePath,
            'keterangan'   => 'hadir',
            'status'       => 'approved'
        ]);

        $lastAbsenDate = $user->last_absen_date instanceof \Carbon\Carbon
            ? $user->last_absen_date->toDateString()
            : $user->last_absen_date;


        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil absen!',
            'streak' => $user->absen_streak
        ]);
    }



    // Halaman awal
    public function berita(Request $request)
    {
        $beritas = Berita::orderBy('created_at', 'desc')->paginate(5);
        return view('siswa.berita', compact('beritas'));
    }

    // Untuk AJAX search realtime
    public function search(Request $request)
    {
        $search = $request->input('search');

        $beritas = Berita::where('judul', 'like', '%' . $search . '%')
            ->orWhere('konten', 'like', '%' . $search . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($beritas);
    }


    public function profile()
    {
        $user = Auth::user();
        return view('siswa.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $user->foto = $filename;
        }

        $user->name = $request->name;
        $user->nohp = $request->nohp;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required'],
            'new_password'          => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }


    public function izin(Request $request)
    {
        $request->validate([
            'tipe'   => 'required|in:izin,sakit',
            'alasan' => 'nullable|string',
            'surat'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $user  = \App\Models\User::find(Auth::id());
        $today = Carbon::today();

        // Cek sudah absen hari ini
        $sudahAda = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->whereIn('keterangan', ['izin', 'sakit', 'hadir'])
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'message' => 'Kamu sudah mengajukan izin, sakit, atau absen hadir hari ini.'
            ], 422);
        }

        if ($request->hasFile('surat')) {
            $file      = $request->file('surat');
            $imageName = 'surat_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = 'surat_sakitIzin/' . $imageName;
            Storage::disk('public')->putFileAs('surat_sakitIzin', $file, $imageName);
            $imageData = $imagePath;
        } else {
            $imageData = null;
        }

        $data = [
            'user_id'      => $user->id,
            'nama'         => $user->name,
            'kelas'        => $user->kelas,
            'jk'           => $user->jenis_kelamin,
            'jurusan'      => $user->jurusan,
            'tanggal'      => Carbon::now()->toDateString(),
            'waktu'        => now()->toTimeString(),
            'latitude'     => 0,
            'longitude'    => 0,
            'lokasi_valid' => true,
            'foto'         => $imageData,
            'status'       => 'pending',
            'keterangan'   => $request->tipe,
            'alasan'       => $request->alasan,
        ];

        \App\Models\Absensi::create($data);

        // ── Kirim Notifikasi hanya ke guru ──
        $suratUrl = $imageData ? asset('storage/' . $imageData) : null;

        $gurus = \App\Models\User::where('usertype', 'guru')
            ->where('kelas', $user->kelas)
            ->get();

        if ($gurus->isNotEmpty()) {
            \Illuminate\Support\Facades\Notification::send(
                $gurus,
                new \App\Notifications\IzinSakitNotification(
                    $user,
                    $request->tipe,
                    $request->alasan ?? '-',
                    $suratUrl
                )
            );
        }

        return response()->json([
            'message' => 'Request berhasil dikirim.'
        ], 200);
    }
}
