<?php
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\RekapAbsensiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    ## Absensi Management
    Route::get('/admin/kehadiran-hari-ini', [KehadiranController::class, 'kehadiranHariIni'])->name('admin.kehadiran');
    Route::get('/admin/kehadiran-hari-ini/data', [KehadiranController::class, 'kehadiranData'])->name('admin.kehadiran.data');

    Route::get('/admin/rekap', [RekapAbsensiController::class, 'rekap'])->name('admin.rekap');
    Route::get('/admin/rekap/export', [RekapAbsensiController::class, 'export'])
        ->name('admin.rekap.export');

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
