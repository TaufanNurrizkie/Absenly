<?php

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        Berita::create([
            'judul' => 'Kegiatan Piket Pagi Dimulai',
            'konten' => 'Mulai minggu ini kegiatan piket pagi dilaksanakan setiap hari Senin-Jumat pukul 06.30.',
            'penulis' => 'OSIS',
            'tanggal_terbit' => now(),
        ]);
    }
}

