<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // XII RPL 1 - Senin
            ['kelas' => 'XII RPL 1', 'hari' => 'Senin', 'mapel' => 'Projek RPL', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Senin', 'mapel' => 'PBO', 'jam_mulai' => '08:40', 'jam_selesai' => '10:10'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Senin', 'mapel' => 'Basis Data', 'jam_mulai' => '10:20', 'jam_selesai' => '11:50'],
        
            // Selasa
            ['kelas' => 'XII RPL 1', 'hari' => 'Selasa', 'mapel' => 'Bahasa Inggris', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Selasa', 'mapel' => 'Matematika', 'jam_mulai' => '08:40', 'jam_selesai' => '10:10'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Selasa', 'mapel' => 'Desain UI/UX', 'jam_mulai' => '10:20', 'jam_selesai' => '11:50'],
        
            // Rabu
            ['kelas' => 'XII RPL 1', 'hari' => 'Rabu', 'mapel' => 'PWPB', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Rabu', 'mapel' => 'Bahasa Indonesia', 'jam_mulai' => '08:40', 'jam_selesai' => '10:10'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Rabu', 'mapel' => 'PKn', 'jam_mulai' => '10:20', 'jam_selesai' => '11:50'],
        
            // Kamis
            ['kelas' => 'XII RPL 1', 'hari' => 'Kamis', 'mapel' => 'Agama', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Kamis', 'mapel' => 'Informatika', 'jam_mulai' => '08:40', 'jam_selesai' => '10:10'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Kamis', 'mapel' => 'Kewirausahaan', 'jam_mulai' => '10:20', 'jam_selesai' => '11:50'],
        
            // Jumat
            ['kelas' => 'XII RPL 1', 'hari' => 'Jumat', 'mapel' => 'Bahasa Sunda', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Jumat', 'mapel' => 'Penjas', 'jam_mulai' => '08:40', 'jam_selesai' => '10:10'],
            ['kelas' => 'XII RPL 1', 'hari' => 'Jumat', 'mapel' => 'Produktif RPL', 'jam_mulai' => '10:20', 'jam_selesai' => '11:50'],
        ];
        

        DB::table('jadwal')->insert($data);
    }
}
