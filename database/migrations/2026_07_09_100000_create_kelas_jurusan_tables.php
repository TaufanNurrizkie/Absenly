<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create kelas table
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // 2. Create jurusans table
        Schema::create('jurusans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        // 3. Migrate existing data from users to new tables
        $existingKelas = DB::table('users')
            ->whereNotNull('kelas')
            ->where('kelas', '!=', '')
            ->distinct()
            ->pluck('kelas');

        foreach ($existingKelas as $nama) {
            DB::table('kelas')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $existingJurusan = DB::table('users')
            ->whereNotNull('jurusan')
            ->where('jurusan', '!=', '')
            ->distinct()
            ->pluck('jurusan');

        foreach ($existingJurusan as $nama) {
            DB::table('jurusans')->insert([
                'nama' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Add foreign key columns to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('foto')->constrained('kelas')->nullOnDelete();
            $table->foreignId('jurusan_id')->nullable()->after('kelas_id')->constrained('jurusans')->nullOnDelete();
        });

        // 5. Map existing string data to foreign keys
        $kelasMap = DB::table('kelas')->pluck('id', 'nama');
        $jurusanMap = DB::table('jurusans')->pluck('id', 'nama');

        $users = DB::table('users')->get(['id', 'kelas', 'jurusan']);
        foreach ($users as $user) {
            $kelasId = $kelasMap[$user->kelas] ?? null;
            $jurusanId = $jurusanMap[$user->jurusan] ?? null;

            if ($kelasId || $jurusanId) {
                DB::table('users')->where('id', $user->id)->update([
                    'kelas_id' => $kelasId,
                    'jurusan_id' => $jurusanId,
                ]);
            }
        }

        // 6. Drop old string columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'jurusan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add old string columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('foto');
            $table->string('jurusan')->nullable()->after('kelas');
        });

        // Map data back
        $users = DB::table('users')->get(['id', 'kelas_id', 'jurusan_id']);
        foreach ($users as $user) {
            $kelasNama = $user->kelas_id ? DB::table('kelas')->where('id', $user->kelas_id)->value('nama') : null;
            $jurusanNama = $user->jurusan_id ? DB::table('jurusans')->where('id', $user->jurusan_id)->value('nama') : null;

            DB::table('users')->where('id', $user->id)->update([
                'kelas' => $kelasNama,
                'jurusan' => $jurusanNama,
            ]);
        }

        // Drop foreign key columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn(['kelas_id', 'jurusan_id']);
        });

        Schema::dropIfExists('jurusans');
        Schema::dropIfExists('kelas');
    }
};
