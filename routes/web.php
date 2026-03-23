<?php

use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\RekapAbsensiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Guru\GuruAbsenController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\GuruSiswaController;
use App\Http\Controllers\Guru\RekapGuruController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLogin'])->name('login');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


## Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    ## Absensi Management
    Route::get('/admin/kehadiran-hari-ini', [KehadiranController::class, 'kehadiranHariIni'])->name('admin.kehadiran');
    Route::get('/admin/kehadiran-hari-ini/data', [KehadiranController::class, 'kehadiranData'])->name('admin.kehadiran.data');
    Route::patch('admin/kehadiran/{id}/approval', [KehadiranController::class, 'updateApproval'])->name('admin.kehadiran.approval');

    ## Berita Management
    Route::get('/admin/berita', [BeritaController::class, 'index'])
        ->name('admin.berita.index');
    Route::post('/admin/berita', [BeritaController::class, 'store'])
        ->name('admin.berita.store');
    Route::get('/admin/berita/{berita}/edit', [BeritaController::class, 'edit'])
        ->name('admin.berita.edit');
    Route::put('/admin/berita/{berita}', [BeritaController::class, 'update'])
        ->name('admin.berita.update');
    Route::delete('/admin/berita/{berita}', [BeritaController::class, 'destroy'])
        ->name('admin.berita.destroy');
    Route::get('/admin/rekap', [RekapAbsensiController::class, 'index'])->name('admin.rekap');
    Route::get('/admin/rekap/export', [RekapAbsensiController::class, 'export'])
        ->name('admin.rekap.export');

    ##jadwal management
    Route::get('admin/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::post('admin/jadwal', [JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::put('admin/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('admin/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('admin.jadwal.delete');


    ## User Management
    Route::get('/admin/users/siswa', [UserController::class, 'indexSiswa'])->name('admin.users.siswa');
    Route::get('/admin/users/guru', [UserController::class, 'indexGuru'])->name('admin.users.guru');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::post('/admin/users/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
});



Route::middleware(['auth', 'guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
});



## Siswa Routes
Route::middleware(['auth', 'siswa'])->group(function () {
    //ROUTE HOME
    Route::get('/siswa/home', [SiswaController::class, 'home'])->name('siswa.home');

    //ROUTE REQUEST
    Route::get('/siswa/request', [SiswaController::class, 'request'])->name('siswa.request');

    //ROUTE ABSEN
    Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
    Route::post('/siswa/absen', [SiswaController::class, 'store']);
    Route::post('/absensi/izin', [SiswaController::class, 'izin'])->name('absensi.izin');


    //ROUTE BERITA
    Route::get('/siswa/berita', [SiswaController::class, 'berita'])->name('siswa.berita');
    Route::get('/siswa/berita-search', [SiswaController::class, 'search'])->name('siswa.berita.search');
    Route::get('/siswa/berita/{id}', [SiswaController::class, 'showBerita'])->name('siswa.berita.show');

    //ROUTE PROFILE
    Route::get('/siswa/profile', [SiswaController::class, 'profile'])->name('siswa.profile');
    Route::put('siswa/update', [SiswaController::class, 'update'])->name('siswa.update');
});

## Guru Routes
Route::middleware(['auth', 'guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    Route::get('/guru/rekap', [GuruController::class, 'rekap'])->name('guru.rekap');
    Route::post('/guru/absensi/{id}/approve', [GuruController::class, 'approve'])->name('guru.absensi.approve');
    Route::post('/guru/absensi/{id}/reject',  [GuruController::class, 'reject'])->name('guru.absensi.reject');

    Route::get('/guru/absen', [GuruAbsenController::class, 'absen'])->name('guru.absen');
    Route::post('/guru/absen', [GuruAbsenController::class, 'store'])->name('guru.absen.store');

    Route::get('/guru/siswa', [GuruSiswaController::class, 'index'])
        ->name('guru.siswa');

    Route::get('/guru/rekap', [RekapGuruController::class, 'index'])->name('guru.rekap');
    Route::get('/guru/rekap/export', [RekapGuruController::class, 'export'])
        ->name('guru.rekap.export');
});


// routes/web.php

// Mark single notifikasi sebagai dibaca
Route::post('/notifikasi/{id}/read', function ($id) {
    auth()->user()->notifications()->findOrFail($id)->markAsRead();
    return response()->json(['ok' => true]);
})->middleware('auth')->name('notifikasi.read');

// Mark semua dibaca
Route::post('/notifikasi/read-all', function () {
    auth()->user()->unreadNotifications->markAsRead();
    return back();
})->middleware('auth')->name('notifikasi.readAll');

// routes/web.php
Route::delete('/notifikasi/{id}', function ($id) {
    auth()->user()->notifications()->findOrFail($id)->delete();
    return response()->json(['ok' => true]);
})->middleware('auth')->name('notifikasi.delete');
