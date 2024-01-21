<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AreaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PohonController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('area')->name('area.')->middleware(['auth:api'])->group(function () {
    Route::get('', [AreaController::class, 'fetch'])->name('fetch');
    Route::post('', [AreaController::class, 'store'])->name('store');
    Route::post('{id}/update', [AreaController::class, 'update'])->name('update');
    Route::delete('{id}', [AreaController::class, 'destroy'])->name('delete');
});

Route::prefix('pohon')->name('pohon.')->middleware(['auth:api'])->group(function () {
    Route::get('', [PohonController::class, 'fetch'])->name('fetch');
    Route::post('', [PohonController::class, 'store'])->name('store');
    Route::post('{id}/update', [PohonController::class, 'update'])->name('update');
    Route::delete('{id}', [PohonController::class, 'destroy'])->name('delete');
});

// Auth API
Route::name('auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('user-auth', [AuthController::class, 'fetch'])->name('fetch');
    });
});
