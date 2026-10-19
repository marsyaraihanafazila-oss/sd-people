<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapAbsensiController;


Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('pegawai', PegawaiController::class)
    ->except(['create', 'show']);

Route::post('/divisi', [PegawaiController::class, 'storeDivisi'])
    ->name('divisi.store');

Route::get('/rekap', [RekapAbsensiController::class, 'index'])
    ->name('rekap.index');

Route::post('/rekap/import', [RekapAbsensiController::class, 'import'])
    ->name('rekap.import');

Route::get('/rekap/export', [RekapAbsensiController::class, 'export'])
    ->name('rekap.export');