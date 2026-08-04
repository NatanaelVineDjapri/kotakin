<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UmkmController;

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

Route::prefix('v1')->group(function () {
    Route::apiResource('kategoris', KategoriController::class);
    Route::apiResource('suppliers', SupplierController::class);

    Route::post('/umkm', [UmkmController::class, 'store']);
    Route::get('/umkm', [UmkmController::class, 'show']);
    Route::get('/umkm/{id}', [UmkmController::class, 'showById']);
    Route::put('/umkm/{id}', [UmkmController::class, 'update']);
    Route::delete('umkm/{id}', [UmkmController::class, 'destroy']);
});
