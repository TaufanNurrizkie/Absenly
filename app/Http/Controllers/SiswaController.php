<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Berita;
use App\Models\Jadwal;
use App\Notifications\PointNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class SiswaController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  KONSTANTA POIN — ubah di sini kalau mau ganti nilai
    // ─────────────────────────────────────────────────────────────
    private const POINT_HADIR_TEPAT  =  10;   // hadir < jam 07:00
    private const POINT_HADIR_TELAT  =   5;   // hadir >= jam 07:00
    private const POINT_IZN_SAKIT    =   2;   // izin/sakit + approved
    private const POINT_ALPHA        = -10;   // tidak hadir (alpha)
    private const POINT_STREAK_BONUS =  20;   // streak kelipatan 5
    private const POINT_LATE_STREAK  = -15;   // telat 3x beruntun
    private const LATE_HOUR          =   7;   // jam batas telat (07:00)
    private const LATE_STREAK_LIMIT  =   3;   // berapa kali telat beruntun

    // ─────────────────────────────────────────────────────────────
    //  DASHBOARD
    // ─────────────────────────────────────────────────────────────
    public function dashboard()
    {
        $user = \App\Models\User::find(Auth::id());
        $absensis = Absensi::where('user_id', $user->id)
            ->whereIn('keterangan', ['hadir', 'izin', 'sakit', 'alpha'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Hitung hari sekolah aktif (Senin–Jumat) sampai hari ini
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

        // Absensi bulan ini
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

        $beritaDashboard = Berita::latest()->take(4)->get();
        $jadwals         = Jadwal::latest()->get();

        return view('siswa.dashboard', compact(
            'user',
            'absensis',
            'beritaDashboard',
            'statsKehadiran',
            'jadwals'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  STORE — absensi hadir + hitung poin
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());
        $now  = Carbon::now();

        // Batasi absensi hanya di hari kerja
        if ($now->isWeekend()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ada absensi di hari weekend.',
            ], 400);
        }

        // Batasi jam
        if ($now->format('H:i') > '23:00') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Absensi sudah ditutup. Maksimal sampai jam 15:00.',
            ], 400);
        }

        $today    = Carbon::today();
        $todayStr = $today->toDateString();

        // Cek sudah absen hari ini
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $todayStr)
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kamu sudah absen hari ini.',
            ], 400);
        }

        // ── Streak (skip weekend) ──────────────────────────────
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

        // ── Proses foto ────────────────────────────────────────
        $imageData = $request->photo;
        $imageName = 'absen_' . time() . '.jpg';
        $imagePath = 'absen_photos/' . $imageName;
        $image     = base64_decode(explode(',', $imageData)[1]);

        $manager = new ImageManager(new Driver());
        $img     = $manager->read($image)->scale(width: 400)->toJpeg(70);
        Storage::disk('public')->put($imagePath, $img);

        // ── Simpan absensi ─────────────────────────────────────
        Absensi::create([
            'user_id'      => $user->id,
            'tanggal'      => $todayStr,
            'waktu'        => $now->toTimeString(),
            'latitude'     => $request->lat,
            'longitude'    => $request->lng,
            'lokasi_valid' => true,
            'foto'         => $imagePath,
            'keterangan'   => 'hadir',
            'status'       => 'approved',
        ]);

        // ── Hitung poin hadir ──────────────────────────────────
        $isLate      = $now->hour >= self::LATE_HOUR;
        $pointEarned = $isLate ? self::POINT_HADIR_TELAT : self::POINT_HADIR_TEPAT;
        $reasons     = [];

        if ($isLate) {
            $reasons[] = 'Hadir (telat) +' . self::POINT_HADIR_TELAT . ' poin';
            $user->late_streak += 1;    // tambah counter telat beruntun
        } else {
            $reasons[] = 'Hadir tepat waktu +' . self::POINT_HADIR_TEPAT . ' poin';
            $user->late_streak = 0;     // reset kalau tepat waktu
        }

        // Penalti telat beruntun (setiap kelipatan LATE_STREAK_LIMIT)
        if ($user->late_streak > 0 && $user->late_streak % self::LATE_STREAK_LIMIT === 0) {
            $pointEarned += self::POINT_LATE_STREAK;
            $reasons[]    = 'Telat ' . self::LATE_STREAK_LIMIT . 'x beruntun ' . self::POINT_LATE_STREAK . ' poin';
        }

        // Bonus streak kelipatan 5
        if ($user->absen_streak % 5 === 0) {
            $pointEarned += self::POINT_STREAK_BONUS;
            $reasons[]    = 'Streak ' . $user->absen_streak . ' hari +' . self::POINT_STREAK_BONUS . ' poin bonus';
        }

        $user->Point += $pointEarned;
        $user->save();

        // ── Kirim notifikasi poin ──────────────────────────────
        $user->notify(new PointNotification(
            pointChange: $pointEarned,
            totalPoints: $user->Point,
            reason:      implode(' | ', $reasons),
        ));

        return response()->json([
            'status'        => 'success',
            'message'       => 'Berhasil absen!',
            'streak'        => $user->absen_streak,
            'points'        => $user->Point,
            'points_earned' => $pointEarned,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  IZIN / SAKIT — hitung poin saat status disetujui
    //  (poin diberikan lewat observer/event saat guru approve,
    //   tapi kita juga sediakan helper publik untuk dipanggil
    //   dari AdminController / GuruController)
    // ─────────────────────────────────────────────────────────────

    /**
     * Dipanggil oleh controller guru/admin setelah mengubah status absensi
     * izin/sakit menjadi approved atau rejected.
     *
     * Contoh pemanggilan dari GuruController:
     *   SiswaController::handleIzinSakitPoint($absensi);
     */
    public static function handleIzinSakitPoint(Absensi $absensi): void
    {
        // Hanya proses izin / sakit
        if (!in_array(strtolower($absensi->keterangan), ['izin', 'sakit'])) {
            return;
        }

        $user   = \App\Models\User::find($absensi->user_id);
        $status = strtolower($absensi->status);

        if ($status === 'approved') {
            $point   = self::POINT_IZN_SAKIT;
            $label   = ucfirst($absensi->keterangan) . ' disetujui +' . $point . ' poin';
        } else {
            // rejected / pending → 0 poin, cukup notif informatif
            $point   = 0;
            $label   = ucfirst($absensi->keterangan) . ' ' . $status . ' (0 poin)';
        }

        $user->Point += $point;
        $user->save();

        $user->notify(new PointNotification(
            pointChange: $point,
            totalPoints: $user->Point,
            reason:      $label,
        ));
    }

    /**
     * Dipanggil oleh scheduler (Console/Commands/ProcessAlpha.php)
     * setiap hari kerja setelah jam sekolah selesai untuk siswa
     * yang sama sekali tidak absen.
     */
    public static function handleAlphaPoint(\App\Models\User $user, string $tanggal): void
    {
        $point = self::POINT_ALPHA;
        $label = 'Tidak hadir (alpha) ' . $point . ' poin — ' . $tanggal;

        $user->Point += $point;
        // Pastikan poin tidak di bawah 0 (opsional, hapus baris ini jika boleh minus)
        // $user->Point = max(0, $user->Point);
        $user->save();

        $user->notify(new PointNotification(
            pointChange: $point,
            totalPoints: $user->Point,
            reason:      $label,
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  ROUTE HANDLER — POST /siswa/izin
    // ─────────────────────────────────────────────────────────────
    public function izin(Request $request)
    {
        $request->validate([
            'tipe'   => 'required|in:izin,sakit',
            'alasan' => 'nullable|string',
            'surat'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user  = \App\Models\User::find(Auth::id());
        $today = Carbon::today();

        $sudahAda = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->whereIn('keterangan', ['izin', 'sakit', 'hadir'])
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'message' => 'Kamu sudah mengajukan izin, sakit, atau absen hadir hari ini.',
            ], 422);
        }

        $imageData = null;
        if ($request->hasFile('surat')) {
            $file      = $request->file('surat');
            $imageName = 'surat_' . time() . '.' . $file->getClientOriginalExtension();
            $imagePath = 'surat_sakitIzin/' . $imageName;
            Storage::disk('public')->putFileAs('surat_sakitIzin', $file, $imageName);
            $imageData = $imagePath;
        }

        $absensi = Absensi::create([
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
        ]);

        // Notif ke guru
        $suratUrl = $imageData ? asset('storage/' . $imageData) : null;
        $gurus    = \App\Models\User::where('usertype', 'guru')
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

        return response()->json(['message' => 'Request berhasil dikirim.'], 200);
    }

    // ─────────────────────────────────────────────────────────────
    //  HALAMAN LAIN (tidak berubah)
    // ─────────────────────────────────────────────────────────────
    public function berita(Request $request)
    {
        $beritas = Berita::orderBy('created_at', 'desc')->paginate(5);
        return view('siswa.berita', compact('beritas'));
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
            'name'   => 'required|string|max:255',
            'nohp'   => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $user->foto = $filename;
        }

        $user->name   = $request->name;
        $user->nohp   = $request->nohp;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}