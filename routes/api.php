<?php

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
Route::get('siswa/bynis/{nis}', [SiswaController::class, 'bynis']);
Route::post('siswa', [SiswaController::class, 'post']);
Route::put('siswa/{id}', [SiswaController::class, 'put']);
Route::delete('siswa/{id}', [SiswaController::class, 'delete']);


//

//tahunajaran
Route::get('tahunajaran', [TahunAjaranController::class, 'index']);
Route::get('tahunajaran/{id}', [TahunAjaranController::class, 'byid']);
Route::post('tahunajaran', [TahunAjaranController::class, 'post']);
Route::put('tahunajaran/{id}', [TahunAjaranController::class, 'put']);
Route::delete('tahunajaran/{id}', [TahunAjaranController::class, 'delete']);
