<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class HariLiburController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $hariLiburs = HariLibur::whereYear('tanggal', $year)
            ->orderBy('tanggal', 'asc')
            ->paginate(20);

        return view('admin.hari-libur.index', compact('hariLiburs', 'year'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_liburs,tanggal',
            'nama' => 'required|string|max:255',
        ]);

        HariLibur::create([
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'is_nasional' => false, // manual input
        ]);

        return redirect()->route('admin.hari-libur.index')
            ->with('success', 'Hari libur berhasil ditambahkan secara manual.');
    }

    public function update(Request $request, HariLibur $hariLibur)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $hariLibur->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.hari-libur.index')
            ->with('success', 'Nama hari libur berhasil diperbarui.');
    }

    public function destroy(HariLibur $hariLibur)
    {
        $hariLibur->delete();

        return redirect()->route('admin.hari-libur.index')
            ->with('success', 'Hari libur berhasil dihapus.');
    }

    public function syncApi()
    {
        try {
            // Kita ambil data libur nasional dari GitHub API V2
            // yang berisi array dengan key tanggal YYYY-MM-DD
            $response = Http::timeout(10)->get('https://raw.githubusercontent.com/guangrei/APIHariLibur_V2/main/holidays.json');
            
            if ($response->successful()) {
                $data = $response->json();
                
                $count = 0;
                foreach ($data as $date => $info) {
                    if (isset($info['summary'])) {
                        // Insert or Update berdasarkan tanggal
                        HariLibur::updateOrCreate(
                            ['tanggal' => $date],
                            [
                                'nama' => $info['summary'],
                                'is_nasional' => true
                            ]
                        );
                        $count++;
                    }
                }
                
                return redirect()->route('admin.hari-libur.index')
                    ->with('success', "Berhasil mensinkronisasi {$count} hari libur nasional dari API.");
            } else {
                return redirect()->route('admin.hari-libur.index')
                    ->with('error', 'Gagal mengambil data dari API Hari Libur.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.hari-libur.index')
                ->with('error', 'Terjadi kesalahan saat sync API: ' . $e->getMessage());
        }
    }
}
