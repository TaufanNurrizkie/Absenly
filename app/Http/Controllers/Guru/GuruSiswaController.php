<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruSiswaController extends Controller
{
    public function index()
    {
        $guru = Auth::user();

        $siswa = User::where('usertype', 'siswa')
            ->where('kelas', $guru->kelas)
            ->where('jurusan', $guru->jurusan)
            ->orderBy('name')
            ->get();

        return view('guru.siswa', compact('siswa', 'guru'));
    }
}
