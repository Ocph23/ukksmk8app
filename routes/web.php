<?php

use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/link', function () {
    Artisan::call('storage:link');
});

Route::get('/auth/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::get('/auth/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/auth/login', [LoginController::class, 'login']);

Route::get('/auth/register', function () {
    return Inertia::render('Auth/Register');
});
Route::post('/auth/register', [RegisterController::class, 'register']);


Route::group(['middleware' => ['auth']], function () {

    Route::get('/admin', function () {
        return Inertia::render('Dashboard');
    });

    Route::get('/admin/jurusan', [\App\Http\Controllers\JurusanController::class, 'indexInertia'])->name('jurusan');
    Route::post('/admin/jurusan', [\App\Http\Controllers\JurusanController::class, 'store'])->name('jurusan.store');
    Route::put('/admin/jurusan/{id}', [\App\Http\Controllers\JurusanController::class, 'update'])->name('jurusan.update');
    Route::delete('/admin/jurusan/{id}', [\App\Http\Controllers\JurusanController::class, 'destroy'])->name('jurusan.destroy');

    Route::get('/admin/tahunajaran', [\App\Http\Controllers\TahunAjaranController::class, 'indexInertia'])->name('tahunajaran');
    Route::post('/admin/tahunajaran', [\App\Http\Controllers\TahunAjaranController::class, 'store'])->name('tahunajaran.store');
    Route::put('/admin/tahunajaran/{id}', [\App\Http\Controllers\TahunAjaranController::class, 'update'])->name('tahunajaran.update');
    Route::delete('/admin/tahunajaran/{id}', [\App\Http\Controllers\TahunAjaranController::class, 'destroy'])->name('tahunajaran.destroy');

    Route::get('/admin/aksesor', [\App\Http\Controllers\AksesorController::class, 'indexInertia'])->name('aksesor');
    Route::post('/admin/aksesor', [\App\Http\Controllers\AksesorController::class, 'store'])->name('aksesor.store');
    Route::put('/admin/aksesor/{id}', [\App\Http\Controllers\AksesorController::class, 'update'])->name('aksesor.update');
    Route::delete('/admin/aksesor/{id}', [\App\Http\Controllers\AksesorController::class, 'destroy'])->name('aksesor.destroy');

    Route::get('/admin/siswa', [\App\Http\Controllers\SiswaController::class, 'indexInertia'])->name('siswa');

    Route::get('/admin/siswa/{id}', function ($id) {
        return Inertia::render('Siswa/Detail', ['siswaId' => $id]);
    })->name('siswadetail');

    Route::get('/admin/paket', [\App\Http\Controllers\PaketController::class, 'indexInertia'])->name('paket');
    Route::post('/admin/paket', [\App\Http\Controllers\PaketController::class, 'store'])->name('paket.store');
    Route::put('/admin/paket/{id}', [\App\Http\Controllers\PaketController::class, 'update'])->name('paket.update');
    Route::put('/admin/paket/{id}/detail', [\App\Http\Controllers\PaketController::class, 'updateDetailInertia'])->name('paket.detail.update');
    Route::delete('/admin/paket/{id}', [\App\Http\Controllers\PaketController::class, 'destroy'])->name('paket.destroy');

    Route::get('/admin/paket/{id}', function ($id) {
        return Inertia::render('Paket/Detail', ['paketId' => $id]);
    });

    Route::get('/admin/kompetensi', function () {
        return Inertia::render('Kompetensi/Index');
    })->name('kompetensi');

    Route::get('/admin/penilaian/{id}', function ($id) {
        return Inertia::render('Penilaian/Index', ['penilaianId' => $id]);
    });

    Route::get('/admin/penilaian/siswa/{siswaId}', function ($siswaId) {
        return Inertia::render('Siswa/Detail', ['siswaId' => $siswaId]);
    })->name('penilaian.siswa');


    //laporan

    Route::get('/admin/lkelulusan', function () {
        return Inertia::render('Laporan/Kelulusan');
    });

    Route::get('/admin/laksesor', function () {
        return Inertia::render('Laporan/Aksesor');
    });
});
