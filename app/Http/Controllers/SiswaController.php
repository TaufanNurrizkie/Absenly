<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Jadwal;
use App\Models\Absensi;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function home()
    {
        $user = \App\Models\User::find(Auth::id());
        $userId = $user->id;
        $stat = [
            'hadir' => Absensi::where('user_id', $userId)->where('keterangan', 'hadir')->count(),
            'izin'  => Absensi::where('user_id', $userId)->where('keterangan', 'izin')->count(),
            'sakit'  => Absensi::where('user_id', $userId)->where('keterangan', 'sakit')->count(),
            'alpa'  => Absensi::where('user_id', $userId)->where('keterangan', 'alpa')->count(),
        ];
        $berita = Berita::orderBy('created_at', 'desc')->limit(3)->get();
        $motivasiList = [
            "Jangan menunda, lakukan sekarang juga.",
            "Kamu hebat! Terus semangat belajar!",
            "Setiap hari adalah kesempatan untuk lebih baik.",
            "Kedisiplinan adalah kunci kesuksesan.",
        ];
        $userKelas = Auth::user()->kelas;

        $hariIni = Carbon::now()->locale('id')->translatedFormat('l'); // contoh hasil: 'Senin'

        $jadwal = Jadwal::where('hari', $hariIni)
            ->orderBy('jam_mulai')
            ->get();

        return view('siswa.home', [
            
            'berita' => $berita,
            'stat' => $stat,
            'jadwal' => $jadwal,
            'motivasi' => $motivasiList[array_rand($motivasiList)]
        ]);
    }


    public function request()
    {
        $status = request('status');

        $requests = Absensi::whereIn('keterangan', ['izin', 'sakit'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();

        

    return view('siswa.request', compact('requests'));
    }

    public function dashboard()
    {

        $user = \App\Models\User::find(Auth::id());
        $absensis = Absensi::where('user_id', $user->id)
            ->where('keterangan', 'hadir')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        return view('siswa.dashboard', compact('user', 'absensis'));
    }


    public function store(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());
        $today = Carbon::today();
        $todayStr = $today->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
    
        // 1. Cek apakah user sudah absen hari ini
        $sudahAbsen = DB::table('absensis')
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();
    
        if ($sudahAbsen) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kamu sudah absen hari ini.'
            ], 400);
        }
    
        // 2. Cek streak hanya jika berhasil absen
        if ($user->last_absen_date === $yesterday) {
            $user->absen_streak += 1;
        } else {
            $user->absen_streak = 1;
        }
        $user->last_absen_date = $todayStr;
        $user->save();
    
        // 3. Simpan foto
        $imageData = $request->photo;
        $imageName = 'absen_' . time() . '.png';
        $imagePath = 'absen_photos/' . $imageName;
    
        $imageData = explode(',', $imageData)[1];
        Storage::disk('public')->put($imagePath, base64_decode($imageData));
    
        // 4. Simpan ke database absensi
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


public function izin(Request $request)
{
    $request->validate([
        'tipe' => 'required|in:izin,sakit',
        'alasan' => 'nullable|string',
        'surat' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ]);

    $user = \App\Models\User::find(Auth::id());

    // Cek apakah sudah pernah izin atau sakit hari ini
    $today = Carbon::today();
    $sudahAda = Absensi::where('user_id', $user->id)
        ->whereDate('tanggal', $today)
        ->whereIn('keterangan', ['izin', 'sakit'])
        ->exists();

    if ($sudahAda) {
        return redirect()->back()->with('error', 'Kamu sudah mengajukan izin atau sakit hari ini.');
    }

    if ($request->hasFile('surat')) {
        $file = $request->file('surat');
        $imageName = 'surat_' . time() . '.' . $file->getClientOriginalExtension();
        $imagePath = 'surat_dokter/' . $imageName;
        Storage::disk('public')->putFileAs('surat_dokter', $file, $imageName);
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

    return redirect()->back()->with('success', 'Request berhasil dikirim.');
}


}
