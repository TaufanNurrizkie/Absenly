<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Siswa
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
            'kelas' => 'XII RPL 1',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'usertype' => 'siswa',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'remember_token' => Str::random(10),
        ]);

        // Guru
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
            'kelas' => null,
            'jurusan' => null,
            'usertype' => 'guru',
            'email_verified_at' => now(),
            'password' => Hash::make('guru123'),
            'remember_token' => Str::random(10),
        ]);

        // Admin
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
            'kelas' => null,
            'jurusan' => null,
            'usertype' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ]);
    }
}
