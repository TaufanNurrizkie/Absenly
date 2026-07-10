<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $rpl = Jurusan::where('nama', 'Rekayasa Perangkat Lunak')->first();
        $kelas = Kelas::where('nama', 'XII RPL 1')->first();

        // ======================
        // SISWA
        // ======================
        User::create([
            'name' => 'Siswa Contoh',
            'email' => 'siswa@example.com',
            'nis' => '1234567890',
            'nohp' => '081234567890',
            'alamat' => 'Jl. Siswa No. 1',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2006-08-15',
            'jenis_kelamin' => 'L',
            'foto' => 'hutao.png',

            'jurusan_id' => $rpl?->id,
            'kelas_id'   => $kelas?->id,

            'usertype' => 'siswa',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);

        // ======================
        // GURU
        // ======================
        User::create([
            'name' => 'Guru Contoh',
            'email' => 'guru@example.com',
            'nis' => '0001',
            'nohp' => '081298765432',
            'alamat' => 'Jl. Guru No. 2',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1985-04-20',
            'jenis_kelamin' => 'P',
            'foto' => null,

            'jurusan_id' => null,
            'kelas_id'   => null,

            'usertype' => 'guru',
            'email_verified_at' => now(),
            'password' => Hash::make('guru123'),
            'remember_token' => Str::random(10),
        ]);

        // ======================
        // ADMIN
        // ======================
        User::create([
            'name' => 'Admin Contoh',
            'email' => 'admin@example.com',
            'nis' => '0002',
            'nohp' => '081299999999',
            'alamat' => 'Jl. Admin No. 3',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'foto' => null,

            'jurusan_id' => null,
            'kelas_id'   => null,

            'usertype' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ]);
    }
}
