<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class RekapAbsensiController extends Controller
{
    public function rekap(Request $request)
    {
        $query = Absensi::with('user');

        // Filter kelas
        if ($request->kelas) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Filter jurusan
        if ($request->jurusan) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('jurusan', $request->jurusan);
            });
        }

        // Filter tanggal
        if ($request->start && $request->end) {
            $query->whereBetween('created_at', [
                $request->start,
                $request->end
            ]);
        }

        // Filter keterangan
        if ($request->keterangan) {
            $query->where('keterangan', $request->keterangan);
        }

        // Search nama / nis
        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->latest()->paginate(15);

        return view('admin.rekap.index', compact('data'));
    }

        public function export(Request $request)
        {
            $query = Absensi::with('user');
    
            // (Sama seperti filter di atas, bisa diulang di sini)
    
            $data = $query->latest()->get();
    
            $csvData = "Nama,NIS,Kelas,Jurusan,Tanggal,Waktu,Keterangan\n";
            foreach ($data as $absen) {
                $csvData .= "{$absen->user->name},{$absen->user->nis},{$absen->user->kelas},{$absen->user->jurusan},{$absen->tanggal},{$absen->waktu},{$absen->keterangan}\n";
            }
    
            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="rekap_absensi.csv"');
        }
}
