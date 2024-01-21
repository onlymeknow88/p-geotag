<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\TanamanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/map', [MapController::class, 'index']);
Route::get('/map/{id}', [MapController::class, 'show'])->name('map.show');
Route::post('/map',  [MapController::class, 'store'])->name('map.store');


Route::resource('/tanaman', TanamanController::class);
Route::get('/tanaman/{id}/map',[TanamanController::class, 'maps'])->name('tanaman.maps');


