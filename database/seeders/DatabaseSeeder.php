<?php

namespace Database\Seeders;

use BeritaSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            JurusanSeeder::class,
            KelasSeeder::class,
            UserSeeder::class,

        ]);
    }
}
