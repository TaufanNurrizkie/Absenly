<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
protected $fillable = ['kelas', 'hari', 'mapel', 'jam_mulai', 'jam_selesai'];

}
