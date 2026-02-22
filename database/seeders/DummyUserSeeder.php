<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyUserSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = ['X-1','X-2','XI-1','XI-2','XII-1','XII-2'];
        $jurusanList = ['RPL','TKJ','AKL'];

        for ($i = 1; $i <= 20; $i++) {

            $kelas = $kelasList[array_rand($kelasList)];
            $jurusan = $jurusanList[array_rand($jurusanList)];

            User::create([
                'name' => 'Dummy Siswa '.$i,
                'email' => 'dummy'.$i.'@absenly.com',
                'nis' => 'D'.str_pad($i, 5, '0', STR_PAD_LEFT),
                'nohp' => '08'.rand(1111111111, 9999999999),
                'alamat' => 'Jl. Dummy No '.$i,
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => Carbon::now()->subYears(rand(15,18))->format('Y-m-d'),
                'jenis_kelamin' => rand(0,1) ? 'L' : 'P',
                'kelas' => $kelas,
                'jurusan' => $jurusan,
                'usertype' => 'siswa',
                'password' => Hash::make('password'),
                'absen_streak' => rand(0,10),
            ]);
        }
    }
}