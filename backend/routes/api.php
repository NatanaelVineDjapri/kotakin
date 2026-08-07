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

        // --- Absensi ---
        Route::prefix('absensi')->middleware('role:admin|super_admin')->group(function () {
            Route::get('rekap/harian',          [AbsensiController::class, 'rekapHarian']);
            Route::get('rekap/harian/export',   [AbsensiController::class, 'exportRekapHarian']);
            Route::get('rekap/mingguan',        [AbsensiController::class, 'rekapMingguan']);
            Route::get('rekap/mingguan/export', [AbsensiController::class, 'exportRekapMingguan']);
            Route::get('rekap/bulanan',         [AbsensiController::class, 'rekapBulanan']);
            Route::get('rekap/bulanan/export',  [AbsensiController::class, 'exportRekapBulanan']);
            Route::get('karyawan/{karyawan}',   [AbsensiController::class, 'detailKaryawan']);
        });

        Route::middleware('role:admin|super_admin')->group(function () {
            Route::apiResource('kategoris', KategoriController::class);
            Route::apiResource('suppliers', SupplierController::class);
            
            Route::get('bahan-bakus/stok-menipis', [BahanBakuController::class, 'stokMenipis']);
            Route::apiResource('bahan-bakus', BahanBakuController::class);
            Route::apiResource('bahan-masuks', BahanMasukController::class);
            Route::apiResource('bahan-keluars', BahanKeluarController::class);
        });

        Route::patch('produks/{produk}/toggle-status', [ProdukController::class, 'toggleStatus']);
        Route::apiResource('produks', ProdukController::class)->only(['index', 'show'])
            ->middleware('role:admin|super_admin|kasir');
        Route::apiResource('produks', ProdukController::class)->except(['index', 'show'])
            ->middleware('role:admin|super_admin');
    });

});

