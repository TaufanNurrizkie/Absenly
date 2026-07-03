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
            // Cek apakah kolom sudah ada untuk avoid error
            if (!Schema::hasColumn('absensis', 'tipe_pulang')) {
                $table->string('tipe_pulang')->nullable()->after('status_pulang'); // izin|sakit
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            if (Schema::hasColumn('absensis', 'tipe_pulang')) {
                $table->dropColumn('tipe_pulang');
            }
        });
    }
};
