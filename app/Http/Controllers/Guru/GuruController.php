<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SiswaController;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $guru    = auth()->user();

        // Query dasar: siswa sekelas & sejurusan dengan guru
        $absensiBase = Absensi::whereDate('tanggal', $tanggal)
            ->whereHas(
                'user',
                fn($q) => $q
                    ->where('kelas',   $guru->kelas)
                    ->where('jurusan', $guru->jurusan)
                    ->where('Usertype', 'siswa')
            );

        // Statistik kartu
        $totalSiswa = User::where('Usertype', 'siswa')
            ->where('kelas',   $guru->kelas)
            ->where('jurusan', $guru->jurusan)
            ->count();

        // Hadir = status disetujui + keterangan hadir
        $sudahAbsen = (clone $absensiBase)
            ->where('status', 'disetujui')
            ->where('keterangan', 'hadir')
            ->count();

        // Izin/Sakit = keterangan izin atau sakit (status apapun kecuali ditolak)
        $izinSakit = (clone $absensiBase)
            ->whereIn('keterangan', ['izin', 'sakit'])
            ->where('status', '!=', 'ditolak')
            ->count();

        // Belum absen = siswa yang sama sekali belum ada record hari ini
        $sudahAbsenIds = (clone $absensiBase)->pluck('user_id');
        $belumAbsen    = $totalSiswa - $sudahAbsenIds->unique()->count();

        // Filter status dari URL (filter_status)
        $filterStatus = $request->input('filter_status');

        $query = clone $absensiBase;

        if ($filterStatus === 'hadir') {
            $query->where('status', 'disetujui')->where('keterangan', 'hadir');
        } elseif ($filterStatus === 'izin') {
            $query->where('keterangan', 'izin');
        } elseif ($filterStatus === 'sakit') {
            $query->where('keterangan', 'sakit');
        } elseif ($filterStatus === 'pending') {
            $query->where('status', 'pending');
        } elseif ($filterStatus === 'ditolak') {
            $query->where('status', 'ditolak');
        }

        $absensiHariIni = $query
            ->with('user')
            ->orderBy('waktu')
            ->paginate(20)
            ->withQueryString(); // agar filter & tanggal ikut di pagination

        return view('guru.dashboard', compact(
            'totalSiswa',
            'sudahAbsen',
            'izinSakit',
            'belumAbsen',
            'absensiHariIni',
            'tanggal'
        ));
    }

    public function approve(Request $request, $id)
    {
        $absen = Absensi::with('user')->findOrFail($id);
        $guru  = auth()->user();

        // Pastikan guru hanya bisa approve siswa kelasnya
        abort_if(
            $absen->user->kelas   !== $guru->kelas ||
                $absen->user->jurusan !== $guru->jurusan,
            403,
            'Tidak berwenang.'
        );

        // Hanya proses poin jika status sebelumnya masih pending
        // (hindari poin dobel kalau guru approve berkali-kali)
        $statusLama = $absen->status;

        $absen->update(['status' => 'approved']);

        if ($statusLama === 'pending') {
            SiswaController::handleIzinSakitPoint($absen);
        }

        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        return redirect()
            ->route('guru.dashboard', ['tanggal' => $tanggal])
            ->with('success', "Absensi {$absen->user->name} berhasil disetujui.");
    }

    public function reject(Request $request, $id)
    {
        $absen = Absensi::with('user')->findOrFail($id);
        $guru  = auth()->user();

        abort_if(
            $absen->user->kelas   !== $guru->kelas ||
                $absen->user->jurusan !== $guru->jurusan,
            403,
            'Tidak berwenang.'
        );

        // Hanya kirim notif jika status sebelumnya masih pending
        $statusLama = $absen->status;

        $absen->update(['status' => 'rejected']);

        if ($statusLama === 'pending') {
            SiswaController::handleIzinSakitPoint($absen);
        }

        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        return redirect()
            ->route('guru.dashboard', ['tanggal' => $tanggal])
            ->with('error', "Absensi {$absen->user->name} ditolak.");
    }
}
