<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PinjamanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/upload-product', [ProductController::class, 'uploadProduct']);
Route::post('/pengajuan', [PinjamanController::class, 'pengajuanPinjaman']);



Route::get('/stock-masuk/{bulan}/{tahun}', [ProductController::class, 'getStockMasukMonth']);
Route::get('/stock-tahun/{tahun}', [ProductController::class, 'getStockMasukYear']);
Route::get('/out-stock-month/{bulan}/{tahun}', [ProductController::class, 'getOutStockMonth']);
Route::get('/out-stock-year/{tahun}', [ProductController::class, 'getOutStockYear']);
Route::get('/product', [ProductController::class, 'getAllProduct']);
Route::get('/pinjaman/{tanggal}', [PinjamanController::class, 'getAllPinjaman']);

