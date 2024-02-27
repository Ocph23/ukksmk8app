<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/admin/jurusan', function () {
    return view('jurusan');
});

Route::get('/admin/tahunajaran', function () {
    return view('tahunajaran');
});

Route::get('/admin/aksesor', function () {
    return view('aksesor');
});

Route::get('/admin/siswa', function () {
    return view('siswa');
});

Route::get('/admin/paket', function () {
    return view('paket');
});

Route::get('/admin/penilaian/{id}', function () {
    return view('penilaian');
});




