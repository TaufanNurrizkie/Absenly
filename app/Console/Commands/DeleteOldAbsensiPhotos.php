<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DeleteOldAbsensiPhotos extends Command
{
    protected $signature = 'absensi:delete-old-photos';
    protected $description = 'Hapus data absensi dan foto yang lebih dari 7 bulan';

    public function handle()
    {
        $limitDate = Carbon::now()->subDays(1);

        $absensis = Absensi::where('created_at', '<', $limitDate)->get();

        foreach ($absensis as $absen) {

            // Hapus foto check-in jika ada
            if ($absen->foto && Storage::disk('public')->exists($absen->foto)) {
                Storage::disk('public')->delete($absen->foto);
            }

            // Hapus foto pulang jika ada
            if ($absen->foto_pulang && Storage::disk('public')->exists($absen->foto_pulang)) {
                Storage::disk('public')->delete($absen->foto_pulang);
            }

            // Hapus data absensi
            $absen->delete();
        }

        $this->info('Data absensi dan foto lebih dari 7 bulan berhasil dihapus.');
    }
}
