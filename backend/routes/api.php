<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UmkmController;

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

        Route::apiResource('kategoris', KategoriController::class);
        Route::apiResource('suppliers', SupplierController::class);
    });

});

<<<<<<< HEAD
Route::prefix('v1')->group(function () {
    Route::apiResource('kategoris', KategoriController::class);
    Route::apiResource('suppliers', SupplierController::class);

    Route::post('/umkm', [UmkmController::class, 'store']);
    Route::get('/umkm', [UmkmController::class, 'show']);
    Route::get('/umkm/{id}', [UmkmController::class, 'showById']);
    Route::put('/umkm/{id}', [UmkmController::class, 'update']);
    Route::delete('umkm/{id}', [UmkmController::class, 'destroy']);
});
=======
>>>>>>> deb3440327747f039d75cf292c6e50f7c4b9e9f2
