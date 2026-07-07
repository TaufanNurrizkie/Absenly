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
        $today = Carbon::today()->toDateString();
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00') . ':00';
        
        $matrix = $siswas->map(function ($siswa) use ($tanggals, $absensiRaw, $today, $jamMasuk) {
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
                'telat'   => 0,
                'bolos'   => 0,
                'days'    => [],
            ];

            foreach ($tanggals as $tgl) {
                $key      = $siswa->id . '_' . $tgl->toDateString();
                $absen    = $absensiRaw->get($key)?->first();
                $status   = $absen ? $absen->keterangan : null;
                $isTelat  = false;
                $isBolos  = false;

                // Logic telat: hadir DAN waktu > jam_masuk
                if ($status === 'hadir' && $absen && $absen->waktu > $jamMasuk) {
                    $isTelat = true;
                    $row['telat']++;
                }

                // Logic bolos: HANYA untuk status 'hadir', waktu_pulang null, DAN tanggal sudah lewat
                // Izin dan Sakit TIDAK pernah dianggap bolos
                $tglString = $tgl->toDateString();
                $isPast = $tglString < $today;
                
                if ($status === 'hadir' && $absen && !$absen->waktu_pulang) {
                    // Jika tanggal sudah lewat, langsung bolos
                    if ($isPast) {
                        $isBolos = true;
                        $row['bolos']++;
                    }
                    // Jika hari ini, cek apakah sudah lewat jam 17:00
                    elseif ($tglString === $today && Carbon::now()->format('H:i') >= '17:00') {
                        $isBolos = true;
                        $row['bolos']++;
                    }
                }

                $row['days'][$tgl->toDateString()] = [
                    'keterangan' => $status,
                    'isTelat'    => $isTelat,
                    'isBolos'    => $isBolos,
                ];

                // Count summary: Bolos dan Telat tidak dihitung sebagai Hadir
                if ($isBolos) {
                    // Sudah dihitung di row['bolos']
                } elseif ($isTelat) {
                    // Sudah dihitung di row['telat']
                } else {
                    match ($status) {
                        'hadir'  => $row['hadir']++,
                        'izin'   => $row['izin']++,
                        'sakit'  => $row['sakit']++,
                        default  => ($tgl->isWeekday() ? $row['alfa']++ : null),
                    };
                }
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

    // ── Export Excel Matrix ─────────────────────────────────────────
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

        $today = Carbon::today()->toDateString();
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00') . ':00';

        // ══ Create Spreadsheet ══
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Absenly System')
            ->setTitle('Rekap Absensi')
            ->setSubject('Laporan Rekap Absensi')
            ->setDescription('Rekap absensi siswa periode ' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y'));

        // ══ HEADER SECTION ══
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'REKAP ABSENSI SISWA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'Periode: ' . $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y'));
        $sheet->getStyle('A2')->getFont()->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        if ($kelas || $jurusan) {
            $filterText = 'Filter: ';
            if ($kelas) $filterText .= "Kelas $kelas ";
            if ($jurusan) $filterText .= "Jurusan $jurusan";
            $sheet->mergeCells('A3:D3');
            $sheet->setCellValue('A3', $filterText);
            $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);
            $headerRow = 5;
        } else {
            $headerRow = 4;
        }

        // ══ TABLE HEADER ══
        $col = 1; // Column A = 1
        $row = $headerRow;

        // Info columns
        $sheet->setCellValueByColumnAndRow($col++, $row, 'No');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'Nama');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'NIS');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'Kelas');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'Jurusan');

        // Date columns
        foreach ($tanggals as $tgl) {
            $sheet->setCellValueByColumnAndRow($col, $row, $tgl->format('d/m'));
            $sheet->setCellValueByColumnAndRow($col, $row + 1, $tgl->translatedFormat('D'));
            $col++;
        }

        // Summary columns
        $summaryStartCol = $col;
        $sheet->setCellValueByColumnAndRow($col++, $row, 'H');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'I');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'S');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'A');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'T');
        $sheet->setCellValueByColumnAndRow($col++, $row, 'B');

        // Merge header rows for date columns
        $sheet->mergeCells([1, $row, 1, $row + 1]); // No
        $sheet->mergeCells([2, $row, 2, $row + 1]); // Nama
        $sheet->mergeCells([3, $row, 3, $row + 1]); // NIS
        $sheet->mergeCells([4, $row, 4, $row + 1]); // Kelas
        $sheet->mergeCells([5, $row, 5, $row + 1]); // Jurusan
        
        // Merge summary columns
        for ($i = 0; $i < 6; $i++) {
            $sheet->mergeCells([$summaryStartCol + $i, $row, $summaryStartCol + $i, $row + 1]);
        }

        // Style header
        $lastCol = $col - 1;
        $headerRange = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(1) . $row . ':' . 
                       \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . ($row + 1);
        
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                           'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);

        // ══ DATA ROWS ══
        $today = Carbon::today()->toDateString();
        $dataRow = $row + 2;
        $no = 1;

        foreach ($siswas as $siswa) {
            $col = 1;
            $hadir = $izin = $sakit = $alfa = $telat = $bolos = 0;

            // No
            $sheet->setCellValueByColumnAndRow($col++, $dataRow, $no++);
            
            // Info
            $sheet->setCellValueByColumnAndRow($col++, $dataRow, $siswa->name);
            $sheet->setCellValueByColumnAndRow($col++, $dataRow, $siswa->nis ?? '-');
            $sheet->setCellValueByColumnAndRow($col++, $dataRow, $siswa->kelas ?? '-');
            $sheet->setCellValueByColumnAndRow($col++, $dataRow, $siswa->jurusan ?? '-');

            // Dates
            foreach ($tanggals as $tgl) {
                $key      = $siswa->id . '_' . $tgl->toDateString();
                $absen    = $absensiRaw->get($key)?->first();
                $status   = $absen ? $absen->keterangan : null;
                $isTelat  = false;
                $isBolos  = false;

                // Logic telat
                if ($status === 'hadir' && $absen && $absen->waktu > $jamMasuk) {
                    $isTelat = true;
                    $telat++;
                }

                // Logic bolos: HANYA untuk status 'hadir', waktu_pulang null, DAN tanggal sudah lewat
                // Izin dan Sakit TIDAK pernah dianggap bolos
                $tglString = $tgl->toDateString();
                $isPast = $tglString < $today;
                
                if ($status === 'hadir' && $absen && !$absen->waktu_pulang) {
                    if ($isPast || ($tglString === $today && Carbon::now()->format('H:i') >= '17:00')) {
                        $isBolos = true;
                        $bolos++;
                    }
                }

                // Display prioritas: Bolos > Telat > Status normal
                $cellValue = '';
                $bgColor = 'FFFFFF';
                $textColor = '000000';

                if ($isBolos) {
                    $cellValue = 'B';
                    $bgColor = 'E9D5FF'; // Purple
                    $textColor = '7C3AED';
                } elseif ($isTelat) {
                    $cellValue = 'T';
                    $bgColor = 'FED7AA'; // Orange
                    $textColor = 'EA580C';
                } elseif ($tgl->isWeekend() && !$status) {
                    $cellValue = '—';
                    $bgColor = 'F3F4F6';
                    $textColor = '9CA3AF';
                } else {
                    $cellValue = match($status) {
                        'hadir'  => 'H',
                        'izin'   => 'I',
                        'sakit'  => 'S',
                        default  => ($tgl->isWeekday() ? 'A' : '—'),
                    };

                    [$bgColor, $textColor] = match($status) {
                        'hadir'  => ['D1FAE5', '059669'], // Green
                        'izin'   => ['FEF3C7', 'D97706'], // Yellow
                        'sakit'  => ['DBEAFE', '2563EB'], // Blue
                        default  => ($tgl->isWeekday() ? ['FEE2E2', 'DC2626'] : ['F3F4F6', '9CA3AF']), // Red / Gray
                    };
                }

                $sheet->setCellValueByColumnAndRow($col, $dataRow, $cellValue);
                $sheet->getStyleByColumnAndRow($col, $dataRow)->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                    'font' => ['color' => ['rgb' => $textColor], 'bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);

                // Count summary: Bolos dan Telat tidak dihitung sebagai Hadir
                if ($isBolos) {
                    // Sudah dihitung di bolos
                } elseif ($isTelat) {
                    // Sudah dihitung di telat
                } else {
                    match ($status) {
                        'hadir'  => $hadir++,
                        'izin'   => $izin++,
                        'sakit'  => $sakit++,
                        default  => ($tgl->isWeekday() ? $alfa++ : null),
                    };
                }

                $col++;
            }

            // Summary
            $summaryData = [$hadir, $izin, $sakit, $alfa, $telat, $bolos];
            $summaryColors = ['D1FAE5', 'FEF3C7', 'DBEAFE', 'FEE2E2', 'FED7AA', 'E9D5FF'];
            
            for ($i = 0; $i < 6; $i++) {
                $sheet->setCellValueByColumnAndRow($col, $dataRow, $summaryData[$i]);
                $sheet->getStyleByColumnAndRow($col, $dataRow)->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $summaryColors[$i]]],
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);
                $col++;
            }

            // Border for row
            $rowRange = 'A' . $dataRow . ':' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . $dataRow;
            $sheet->getStyle($rowRange)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]]
            ]);

            // Zebra striping
            if ($dataRow % 2 === 0) {
                $infoRange = 'A' . $dataRow . ':E' . $dataRow;
                $sheet->getStyle($infoRange)->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']]
                ]);
            }

            $dataRow++;
        }

        // ══ LEGEND ══
        $legendRow = $dataRow + 2;
        $sheet->setCellValue('A' . $legendRow, 'Keterangan:');
        $sheet->getStyle('A' . $legendRow)->getFont()->setBold(true);

        $legends = [
            ['H', 'Hadir', 'D1FAE5'],
            ['T', 'Telat', 'FED7AA'],
            ['B', 'Bolos', 'E9D5FF'],
            ['I', 'Izin', 'FEF3C7'],
            ['S', 'Sakit', 'DBEAFE'],
            ['A', 'Alpha', 'FEE2E2'],
        ];

        $legendRow++;
        foreach ($legends as $legend) {
            $sheet->setCellValue('A' . $legendRow, $legend[0]);
            $sheet->setCellValue('B' . $legendRow, $legend[1]);
            $sheet->getStyle('A' . $legendRow)->applyFromArray([
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => $legend[2]]],
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ]);
            $legendRow++;
        }

        // ══ AUTO WIDTH ══
        $sheet->getColumnDimension('A')->setWidth(6);  // No
        $sheet->getColumnDimension('B')->setWidth(25); // Nama
        $sheet->getColumnDimension('C')->setWidth(12); // NIS
        $sheet->getColumnDimension('D')->setWidth(10); // Kelas
        $sheet->getColumnDimension('E')->setWidth(12); // Jurusan

        // Date columns
        for ($i = 6; $i <= 6 + $tanggals->count() - 1; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setWidth(5);
        }

        // Summary columns
        for ($i = 0; $i < 6; $i++) {
            $sheet->getColumnDimensionByColumn($summaryStartCol + $i)->setWidth(5);
        }

        // Freeze panes
        $sheet->freezePane('F' . ($headerRow + 2)); // Freeze hingga kolom E dan header

        // ══ GENERATE FILE ══
        $filename = 'Rekap_Absensi_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
