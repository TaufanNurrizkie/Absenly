<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->boolean('terlambat')->default(false)->after('waktu');
        });

        // Inisialisasi data lama supaya tetap sesuai dengan jam masuk saat ini
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '07:00') . ':00';
        \Illuminate\Support\Facades\DB::table('absensis')
            ->where('keterangan', 'hadir')
            ->whereNotNull('waktu')
            ->where('waktu', '>', $jamMasuk)
            ->update(['terlambat' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn('terlambat');
        });
    }
};
