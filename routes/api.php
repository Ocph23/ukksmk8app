<?php

use App\Http\Controllers\AksesorController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PenilaianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//siswa
Route::get('siswa', [SiswaController::class, 'index']);
Route::get('siswa/{id}', [SiswaController::class, 'byid']);
Route::get('siswa/bytahunajaran/{id}', [SiswaController::class, 'bytahunajaran']);
Route::get('siswa/bynis/{nis}', [SiswaController::class, 'bynis']);
Route::post('siswa', [SiswaController::class, 'post']);
Route::put('siswa/{id}', [SiswaController::class, 'put']);
Route::put('siswa/{id}/sertifikat', [SiswaController::class, 'updateSertifikat']);
Route::delete('siswa/{id}', [SiswaController::class, 'delete']);

//

//tahunajaran
Route::get('tahunajaran', [TahunAjaranController::class, 'index']);
Route::get('tahunajaran/{id}', [TahunAjaranController::class, 'byid']);
Route::post('tahunajaran', [TahunAjaranController::class, 'post']);
Route::put('tahunajaran/{id}', [TahunAjaranController::class, 'put']);
Route::delete('tahunajaran/{id}', [TahunAjaranController::class, 'delete']);



//jurusan
Route::get('jurusan', [JurusanController::class, 'index']);
Route::get('jurusan/{id}', [JurusanController::class, 'byid']);
Route::post('jurusan', [JurusanController::class, 'post']);
Route::put('jurusan/{id}', [JurusanController::class, 'put']);
Route::delete('jurusan/{id}', [JurusanController::class, 'delete']);


//jurusan
Route::get('aksesor', [AksesorController::class, 'index']);
Route::get('aksesor/{id}', [AksesorController::class, 'byid']);
Route::post('aksesor', [AksesorController::class, 'post']);
Route::put('aksesor/{id}', [AksesorController::class, 'put']);
Route::delete('aksesor/{id}', [AksesorController::class, 'delete']);


//paket
Route::get('paket', [PaketController::class, 'index']);
Route::get('paket/{id}', [PaketController::class, 'byid']);
Route::get('paket/bytahunajaran/{id}', [PaketController::class, 'bytahunajaran']);
Route::post('paket', [PaketController::class, 'post']);
Route::put('paket/{id}', [PaketController::class, 'put']);
Route::put('paket/{id}/detail', [PaketController::class, 'putDetail']);
Route::delete('paket/{id}', [PaketController::class, 'delete']);




//penilaian
Route::get('penilaian', [PenilaianController::class, 'index']);
Route::get('penilaian/{id}', [PenilaianController::class, 'byid']);
Route::get('penilaian/siswa/{id}', [PenilaianController::class, 'bysiswaid']);
Route::post('penilaian', [PenilaianController::class, 'post']);
Route::put('penilaian/{id}', [PenilaianController::class, 'put']);
Route::delete('penilaian/{id}', [PenilaianController::class, 'delete']);

//penilaian
Route::get('laporan/{ta}/{jurusan}', [LaporanController::class, 'laporanKelulusan']);