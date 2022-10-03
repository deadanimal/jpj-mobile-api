<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EaduanController;
use App\Http\Controllers\JpjMobileApiController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SecCheckController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/upupgambar', function () {
    return view('test_upload');
})->name('upupgambar');

Route::post('/hntrgmbr', [EaduanController::class, 'upld_images']);

Route::get('/semakidawam2', [AuthenticationController::class, 'semakIdAwam']);
Route::get('/semakidawam', [AuthenticationController::class, 'semakId']);

Route::get('/checkemail', [SecCheckController::class, 'checkemail']);

Route::get('/check123', [AuthenticationController::class, 'check123']);
Route::get('/kmkadu', [EaduanController::class, 'kmkadu']);

Route::get('/direktori', [JpjMobileApiController::class, 'direktori_jpj']);
Route::get('/checksajo', [EaduanController::class, 'checksajo']);

Route::get('/jpjinfo-api/apps/semakstatuslesen', [JpjMobileApiController::class, 'semakstatusbank']);

Route::post('/testnfs', [LogController::class, 'nfs']);
Route::get('/nfs', function () {
    return view('nfs');
});
require __DIR__ . '/auth.php';
