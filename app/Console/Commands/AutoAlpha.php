<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoAlpha extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    

    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Execute the console command.
     */
    protected $signature = 'auto:alpha';
    protected $description = 'Insert alpha for students who did not attend';

    public function handle()
    {
        $today = now()->toDateString();

        $siswaBelumAbsen = User::where('usertype', 'siswa')
            ->whereDoesntHave('absensis', function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            })
            ->get();

        foreach ($siswaBelumAbsen as $user) {
            Absensi::create([
                'user_id' => $user->id,
                'keterangan' => 'alpha',
                'waktu' => now()->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return Command::SUCCESS;
    }
}
