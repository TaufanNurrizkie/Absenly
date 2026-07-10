<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = [
            'Rekayasa Perangkat Lunak',
            'Teknik Komputer dan Jaringan',
            'Multimedia',
            'Akuntansi',
            'Administrasi Perkantoran',
        ];

        foreach ($jurusan as $nama) {
            Jurusan::firstOrCreate([
                'nama' => $nama,
            ]);
        }
    }
}
