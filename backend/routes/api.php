<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\BahanMasukController;
use App\Http\Controllers\BahanKeluarController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JadwalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // --- Auth (public) ---
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    // --- Auth (protected) ---
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me',     [AuthController::class, 'me']);

                    // --- Jadwal ---
        Route::apiResource('jadwal', JadwalController::class);
        Route::get('jadwal/kalender', [JadwalController::class, 'kalenderMingguan']);
        });

        // --- Absensi ---
        Route::prefix('absensi')->middleware('role:admin|super_admin')->group(function () {
            Route::get('jadwal/kalender', [JadwalController::class, 'kalenderMingguan']);
            Route::apiResource('jadwal', JadwalController::class)->except(['index']); 
            
            Route::get('rekap/harian',          [AbsensiController::class, 'rekapHarian']);
            Route::get('rekap/harian/export',   [AbsensiController::class, 'exportRekapHarian']);
            Route::get('rekap/mingguan',        [AbsensiController::class, 'rekapMingguan']);
            Route::get('rekap/mingguan/export', [AbsensiController::class, 'exportRekapMingguan']);
            Route::get('rekap/bulanan',         [AbsensiController::class, 'rekapBulanan']);
            Route::get('rekap/bulanan/export',  [AbsensiController::class, 'exportRekapBulanan']);

            // ---Manajemem karyawan ---
            Route::apiResource('karyawan', KaryawanController::class);
            Route::get('karyawan/{karyawan}',   [AbsensiController::class, 'detailKaryawan']);
            Route::get('karyawan/{karyawan}/jadwal', [JadwalController::class, 'byKaryawan']);

            Route::apiResource('kategoris', KategoriController::class);
            Route::apiResource('suppliers', SupplierController::class);
            Route::apiResource('produks', ProdukController::class);
            Route::apiResource('bahan-bakus', BahanBakuController::class);
            Route::apiResource('bahan-masuks', BahanMasukController::class);
            Route::apiResource('bahan-keluars', BahanKeluarController::class);
    });

});
