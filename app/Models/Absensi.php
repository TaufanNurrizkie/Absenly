<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu',
        'waktu_pulang',
        'latitude',
        'longitude',
        'lokasi_valid',
        'foto',
        'keterangan',
        'status',
        'alasan',
        'status_pulang',
        'tipe_pulang',
        'alasan_pulang'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
