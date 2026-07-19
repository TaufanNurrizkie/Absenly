<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    protected $fillable = ['tanggal', 'nama', 'is_nasional'];

    protected $casts = [
        'tanggal' => 'date',
        'is_nasional' => 'boolean',
    ];

    /**
     * Cek apakah tanggal tertentu adalah hari libur nasional.
     * Mengembalikan instance HariLibur atau null.
     */
    public static function isHoliday($date)
    {
        $dateStr = Carbon::parse($date)->toDateString();
        return \Illuminate\Support\Facades\Cache::remember("holiday_{$dateStr}", 3600, function () use ($dateStr) {
            return self::whereDate('tanggal', $dateStr)->first();
        });
    }

    /**
     * Cek apakah hari ini adalah hari libur nasional.
     */
    public static function todayHoliday()
    {
        return self::isHoliday(Carbon::today());
    }
}
