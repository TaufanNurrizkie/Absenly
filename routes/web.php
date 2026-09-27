<?php

use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\HariLiburController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\RekapAbsensiController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Guru\GuruAbsenController;
use App\Http\Controllers\Guru\GuruController;
use App\Http\Controllers\Guru\GuruSiswaController;
use App\Http\Controllers\Guru\RekapGuruController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Models\User;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\JamKosongController;
use App\Http\Controllers\DeployController;
use App\Notifications\JamkosNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLogin'])->name('showlogin');



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
    Route::patch('admin/kehadiran/{id}/approval-pulang', [KehadiranController::class, 'updateApprovalPulang'])
        ->name('admin.kehadiran.approval-pulang');

    // Absen Pulang
    Route::get('/admin/absen-pulang', [KehadiranController::class, 'absenPulang'])->name('admin.absen-pulang');
    Route::get('/admin/absen-pulang/data', [KehadiranController::class, 'absenPulangData'])->name('admin.absen-pulang.data');

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
    Route::post('/admin/rekap/update-matrix', [RekapAbsensiController::class, 'updateMatrix'])->name('admin.rekap.update-matrix');
    Route::get('/admin/rekap/export', [RekapAbsensiController::class, 'export'])
        ->name('admin.rekap.export');

    ##jadwal management
    Route::get('admin/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::post('admin/jadwal', [JadwalController::class, 'store'])->name('admin.jadwal.store');
    Route::put('admin/jadwal/{jadwal}', [JadwalController::class, 'update'])->name('admin.jadwal.update');
    Route::delete('admin/jadwal/{jadwal}', [JadwalController::class, 'destroy'])->name('admin.jadwal.delete');

    ## Hari Libur Management
    Route::get('/admin/hari-libur', [HariLiburController::class, 'index'])->name('admin.hari-libur.index');
    Route::post('/admin/hari-libur', [HariLiburController::class, 'store'])->name('admin.hari-libur.store');
    Route::put('/admin/hari-libur/{hariLibur}', [HariLiburController::class, 'update'])->name('admin.hari-libur.update');
    Route::delete('/admin/hari-libur/{hariLibur}', [HariLiburController::class, 'destroy'])->name('admin.hari-libur.destroy');
    Route::post('/admin/hari-libur/sync', [HariLiburController::class, 'syncApi'])->name('admin.hari-libur.sync');


    ## User Management
    Route::get('/admin/users/siswa', [UserController::class, 'indexSiswa'])->name('admin.users.siswa');
    // Route::get('/admin/users/guru', [UserController::class, 'indexGuru'])->name('admin.users.guru');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::post('/admin/users/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
    Route::post('/admin/users/import', [UserController::class, 'import'])->name('admin.users.import');
    Route::post('/admin/guru/import', [UserController::class, 'importGuru'])->name('admin.guru.import');

    ## Settings Management
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');

    ## Kelas Management
    Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
    Route::post('/admin/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
    Route::put('/admin/kelas/{kela}', [KelasController::class, 'update'])->name('admin.kelas.update');
    Route::delete('/admin/kelas/{kela}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');

    ## Jurusan Management
    Route::get('/admin/jurusan', [JurusanController::class, 'index'])->name('admin.jurusan.index');
    Route::post('/admin/jurusan', [JurusanController::class, 'store'])->name('admin.jurusan.store');
    Route::put('/admin/jurusan/{jurusan}', [JurusanController::class, 'update'])->name('admin.jurusan.update');
    Route::delete('/admin/jurusan/{jurusan}', [JurusanController::class, 'destroy'])->name('admin.jurusan.destroy');

    ## Scanner
    Route::get('/admin/scan', [AdminController::class, 'scan'])->name('admin.scan');
    Route::post('/admin/scan/info', [AdminController::class, 'scanInfo'])->name('admin.scan.info');
    Route::post('/admin/scan/process', [AdminController::class, 'processScan'])->name('admin.scan.process');
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
    Route::get('/siswa/absensi-status', [SiswaController::class, 'absensiStatus'])->name('siswa.absensi.status');
    Route::post('/siswa/absen', [SiswaController::class, 'store']);
    Route::post('/siswa/absen-pulang', [SiswaController::class, 'absenPulang'])->name('siswa.absen.pulang');
    Route::post('/absensi/izin', [SiswaController::class, 'izin'])->name('absensi.izin');


    //ROUTE BERITA
    Route::get('/siswa/berita', [SiswaController::class, 'berita'])->name('siswa.berita');
    Route::get('/siswa/berita-search', [SiswaController::class, 'search'])->name('siswa.berita.search');
    Route::get('/siswa/berita/{id}', [SiswaController::class, 'showBerita'])->name('siswa.berita.show');

    //ROUTE PROFILE
    Route::get('/siswa/profile', [SiswaController::class, 'profile'])->name('siswa.profile');
    Route::put('siswa/update', [SiswaController::class, 'update'])->name('siswa.update');
    Route::put('/siswa/password', [SiswaController::class, 'updatePassword'])->name('siswa.password.update');
    Route::get('/siswa/qr/download', [SiswaController::class, 'downloadQr'])->name('siswa.qr.download');


    Route::get('/siswa/notif/read-all', [NotifikasiController::class, 'readAll'])->name('siswa.notif.readAll');
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




// Mark single notifikasi sebagai dibaca -> langsung hapus
Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'read'])->middleware('auth')->name('notifikasi.read');

// Mark semua dibaca
Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])->middleware('auth')->name('notifikasi.readAll');

// Hapus Notifikasi
Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->middleware('auth')->name('notifikasi.delete');

// Kirim Jam Kosong
Route::post('/siswa/jamkos/kirim', [JamKosongController::class, 'kirim'])->middleware('auth')->name('siswa.jamkos.kirim');

// Remote Artisan Deploy Handler (untuk cPanel tanpa SSH/Terminal)
Route::get('/deploy/artisan', [DeployController::class, 'handle'])->name('deploy.artisan');

