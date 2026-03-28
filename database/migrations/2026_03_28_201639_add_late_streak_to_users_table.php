<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom yang belum ada di tabel users:
     *  - absen_streak    (jika belum ada)
     *  - last_absen_date (jika belum ada)
     *  - late_streak     (baru — untuk hitung telat beruntun)
     *
     * Kolom Point (kapital) sudah ada dengan default 100, tidak diubah.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah hanya jika belum ada
            if (!Schema::hasColumn('users', 'absen_streak')) {
                $table->integer('absen_streak')->default(0)->after('Point');
            }
            if (!Schema::hasColumn('users', 'last_absen_date')) {
                $table->date('last_absen_date')->nullable()->after('absen_streak');
            }
            if (!Schema::hasColumn('users', 'late_streak')) {
                $table->integer('late_streak')->default(0)->after('last_absen_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = ['late_streak'];

            // Hanya drop kolom yang memang ditambahkan di migration ini
            // absen_streak & last_absen_date mungkin sudah ada sebelumnya
            $table->dropColumn($cols);
        });
    }
};