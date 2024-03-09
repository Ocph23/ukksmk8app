<?php

use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/link', function () {        
    Artisan::call('storage:link');
  });

 Route::get('/auth/login', [LoginController::class, 'show'])->name('login');
Route::get('/auth/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/auth/login', [LoginController::class, 'login']);

Route::get('/auth/register', [RegisterController::class, 'show']);
Route::post('/auth/register', [RegisterController::class, 'register']);


Route::group(['middleware' => ['auth']], function () {

    Route::get('/admin', function () {
        return view('home');
    });

    Route::get('/admin/jurusan', [JurusanController::class, 'getJurusan']);

    Route::get('/admin/tahunajaran', function () {
        return view('tahunajaran');
    });

    Route::get('/admin/aksesor', function () {
        return view('aksesor');
    });

    Route::get('/admin/siswa', function () {
        return view('siswa');
    })->name('siswa');

    Route::get('/admin/siswa/{id}', function () {
        return view('siswadetail');
    })->name('siswadetail');

    Route::get('/admin/paket', function () {
        return view('paket');
    });

    Route::get('/admin/paket/{id}', function () {
        return view('paketdetail');
    });

    Route::get('/admin/penilaian/{id}', function () {
        return view('penilaian');
    });


    //laporan
    
    Route::get('/admin/lkelulusan', function () {
        return view('laporanKelulusan');
    });

    Route::get('/admin/laksesor', function () {
        return view('laporanAksesor');
    });


});
