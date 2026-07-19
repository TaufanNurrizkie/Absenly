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
            $table->index('tanggal');
            $table->index('user_id');
            $table->index('keterangan');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('usertype');
            $table->index('kelas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['keterangan']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['usertype']);
            $table->dropIndex(['kelas_id']);
        });
    }
};

