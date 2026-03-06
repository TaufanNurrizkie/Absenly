<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RekapAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $mode    = $request->get('mode', 'mingguan');
        $kelas   = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $search  = $request->get('search');

        // ── Tentukan rentang tanggal ──────────────────────────────
        // Selalu inisialisasi kedua variabel
        $bulan  = null;
        $minggu = null;

        if ($mode === 'bulanan') {
            $bulan  = $request->get('bulan', now()->format('Y-m'));
            $start  = Carbon::parse($bulan . '-01')->startOfMonth();
            $end    = $start->copy()->endOfMonth();
        } else {
            // Mingguan: Senin s/d Minggu minggu ini
            $minggu = $request->get('minggu', now()->startOfWeek()->format('Y-m-d'));
            $start  = Carbon::parse($minggu)->startOfWeek(Carbon::MONDAY);
            $end    = $start->copy()->endOfWeek(Carbon::SUNDAY);
        }

        // Batasi maksimal 31 hari supaya tabel tidak terlalu lebar
        if ($start->diffInDays($end) > 30) {
            $end = $start->copy()->addDays(30);
        }

        // ── Buat array kolom tanggal ──────────────────────────────
        $period  = CarbonPeriod::create($start, $end);
        $tanggals = collect($period)->map(fn($d) => $d->copy());

        // ── Query siswa ───────────────────────────────────────────
        $siswaQuery = User::where('usertype', 'siswa')
            ->when($kelas,   fn($q) => $q->where('kelas', $kelas))
            ->when($jurusan, fn($q) => $q->where('jurusan', $jurusan))
            ->when($search,  fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis',  'like', "%{$search}%");
            }))
            ->orderBy('kelas')
            ->orderBy('name');

        $siswas = $siswaQuery->get();

        // ── Query absensi dalam rentang ───────────────────────────
        $absensiRaw = Absensi::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->whereIn('user_id', $siswas->pluck('id'))
            ->get()
            ->groupBy(fn($a) => $a->user_id . '_' . $a->tanggal);
        // key: "userId_YYYY-MM-DD" => collection absensi

        // ── Susun data matrix ─────────────────────────────────────
        $matrix = $siswas->map(function ($siswa) use ($tanggals, $absensiRaw) {
            $row = [
                'id'      => $siswa->id,
                'name'    => $siswa->name,
                'nis'     => $siswa->nis ?? '-',
                'kelas'   => $siswa->kelas ?? '-',
                'jurusan' => $siswa->jurusan ?? '-',
                'hadir'   => 0,
                'izin'    => 0,
                'sakit'   => 0,
                'alfa'    => 0,
                'days'    => [],
            ];

            foreach ($tanggals as $tgl) {
                $key    = $siswa->id . '_' . $tgl->toDateString();
                $absen  = $absensiRaw->get($key)?->first();
                $status = $absen ? $absen->keterangan : null;

                $row['days'][$tgl->toDateString()] = $status;

                match ($status) {
                    'hadir'  => $row['hadir']++,
                    'izin'   => $row['izin']++,
                    'sakit'  => $row['sakit']++,
                    default  => ($tgl->isWeekday() ? $row['alfa']++ : null),
                };
            }

            return $row;
        });

        // ── Pagination matrix (5 per halaman) ────────────────────
        $perPage     = 10;
        $currentPage = $request->get('page', 1);
        $total       = $matrix->count();
        $sliced      = $matrix->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $matrix = new LengthAwarePaginator(
            $sliced,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // ── Distinct kelas & jurusan untuk dropdown ───────────────
        $kelasList   = User::where('usertype', 'siswa')->whereNotNull('kelas')
                           ->distinct()->orderBy('kelas')->pluck('kelas');
        $jurusanList = User::where('usertype', 'siswa')->whereNotNull('jurusan')
                           ->distinct()->orderBy('jurusan')->pluck('jurusan');

        // ── Navigasi minggu/bulan ─────────────────────────────────
        $prevNav = $mode === 'bulanan'
            ? $start->copy()->subMonth()->format('Y-m')
            : $start->copy()->subWeek()->format('Y-m-d');

        $nextNav = $mode === 'bulanan'
            ? $start->copy()->addMonth()->format('Y-m')
            : $start->copy()->addWeek()->format('Y-m-d');

        return view('admin.rekap.index', compact(
            'matrix', 'tanggals', 'mode',
            'start', 'end',
            'kelas', 'jurusan', 'search',
            'kelasList', 'jurusanList',
            'prevNav', 'nextNav',
            'bulan', 'minggu'
        ));
    }

    // ── Export CSV Matrix ─────────────────────────────────────────
    public function export(Request $request)
    {
        $mode    = $request->get('mode', 'mingguan');
        $kelas   = $request->get('kelas');
        $jurusan = $request->get('jurusan');
        $search  = $request->get('search');

        if ($mode === 'bulanan') {
            $bulan = $request->get('bulan', now()->format('Y-m'));
            $start = Carbon::parse($bulan . '-01')->startOfMonth();
            $end   = $start->copy()->endOfMonth();
        } else {
            $minggu = $request->get('minggu', now()->startOfWeek()->format('Y-m-d'));
            $start  = Carbon::parse($minggu)->startOfWeek(Carbon::MONDAY);
            $end    = $start->copy()->endOfWeek(Carbon::SUNDAY);
        }

        $period   = CarbonPeriod::create($start, $end);
        $tanggals = collect($period)->map(fn($d) => $d->copy());

        $siswas = User::where('usertype', 'siswa')
            ->when($kelas,   fn($q) => $q->where('kelas', $kelas))
            ->when($jurusan, fn($q) => $q->where('jurusan', $jurusan))
            ->when($search,  fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis',  'like', "%{$search}%");
            }))
            ->orderBy('kelas')->orderBy('name')
            ->get();

        $absensiRaw = Absensi::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->whereIn('user_id', $siswas->pluck('id'))
            ->get()
            ->groupBy(fn($a) => $a->user_id . '_' . $a->tanggal);

        // Header CSV
        $header = ['Nama', 'NIS', 'Kelas', 'Jurusan'];
        foreach ($tanggals as $tgl) {
            $header[] = $tgl->format('d/m');
        }
        $header = array_merge($header, ['Total Hadir', 'Izin', 'Sakit', 'Alfa']);

        $rows = [];
        foreach ($siswas as $siswa) {
            $row    = [$siswa->name, $siswa->nis ?? '-', $siswa->kelas ?? '-', $siswa->jurusan ?? '-'];
            $hadir  = $izin = $sakit = $alfa = 0;

            foreach ($tanggals as $tgl) {
                $key    = $siswa->id . '_' . $tgl->toDateString();
                $absen  = $absensiRaw->get($key)?->first();
                $status = $absen ? $absen->keterangan : null;

                $row[] = match($status) {
                    'hadir'  => 'H',
                    'izin'   => 'I',
                    'sakit'  => 'S',
                    default  => ($tgl->isWeekday() ? 'A' : '-'),
                };

                match ($status) {
                    'hadir'  => $hadir++,
                    'izin'   => $izin++,
                    'sakit'  => $sakit++,
                    default  => ($tgl->isWeekday() ? $alfa++ : null),
                };
            }

            $rows[] = array_merge($row, [$hadir, $izin, $sakit, $alfa]);
        }

        $filename = 'rekap_' . $mode . '_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';

        $callback = function () use ($header, $rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($file, $header);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
