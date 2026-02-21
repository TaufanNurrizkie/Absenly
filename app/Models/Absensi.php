<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu',
        'latitude',
        'longitude',
        'lokasi_valid',
        'foto',
        'keterangan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
