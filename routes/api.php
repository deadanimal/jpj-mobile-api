<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\EaduanController;
use App\Http\Controllers\eHadirController;
use App\Http\Controllers\JpjMobileApiController;
use App\Http\Controllers\SmartQv2Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/semak_id_awam', [AuthenticationController::class, 'semakIdAwam']);
Route::post('/register_id_awam', [AuthenticationController::class, 'registerIdAwam']);
Route::post('/log_masuk', [AuthenticationController::class, 'firstTimeLogin']);
Route::post('/soalan_keselamatan', [AuthenticationController::class, 'soalanKeselamatan']);
Route::post('/change_password_id_awam', [AuthenticationController::class, 'changePasswordIdAwam']);
Route::post('/reset_password_id_awam', [AuthenticationController::class, 'resetPasswordIdAwam']);

Route::post('/simpan_aduan', [EaduanController::class, 'simpan_aduan']);
Route::post('/upld_images', [EaduanController::class, 'upld_images']);
Route::post('/get_status_aduan', [EaduanController::class, 'get_status_aduan']);
Route::post('/kemaskini_aduan', [EaduanController::class, 'kemaskini_aduan']);

Route::get('/semakstatusbank', [JpjMobileApiController::class, 'semakstatusbank']);
Route::post('/semaksaman', [JpjMobileApiController::class, 'semaksaman']);
Route::post('/pendaftaran', [JpjMobileApiController::class, 'pendaftaran']);
Route::post('/semakan_tarikh_luput_lesen_kenderaan_motor', [JpjMobileApiController::class, 'semakan_tarikh_luput_lesen_kenderaan_motor']);
Route::post('/semakan_nombor_pendaftaran', [JpjMobileApiController::class, 'semakan_nombor_pendaftaran']);
Route::post('/semakan_status_permohonan_penubuhan_institut_memandu', [JpjMobileApiController::class, 'semakan_status_permohonan_penubuhan_institut_memandu']);
Route::post('/semakan_tarikh_luput_lesen_memandu', [JpjMobileApiController::class, 'semakan_tarikh_luput_lesen_memandu']);
Route::post('/semakan_status_senarai_hitam', [JpjMobileApiController::class, 'semakan_status_senarai_hitam']);
Route::post('/semakan_dimerit', [JpjMobileApiController::class, 'semakan_dimerit']);
Route::post('/semakan_pertukaran_lesen_memandu_luar_negara', [JpjMobileApiController::class, 'semakan_pertukaran_lesen_memandu_luar_negara']);
Route::post('/semakan_ujian_memandu', [JpjMobileApiController::class, 'semakan_ujian_memandu']);
Route::get('/direktori', [JpjMobileApiController::class, 'direktori_jpj']);

Route::post('/daftar_kehadiran', [eHadirController::class, 'daftar_kehadiran']);
Route::post('/senarai_aktiviti_hadir', [eHadirController::class, 'senarai_aktiviti_hadir']);
Route::post('/senarai_aktiviti', [eHadirController::class, 'senarai_aktiviti']);
Route::post('/senarai_urusetia1', [eHadirController::class, 'senarai_urusetia1']);
Route::post('/tambah_urusetia', [eHadirController::class, 'tambah_urusetia']);
Route::post('/senarai_kehadiran', [eHadirController::class, 'senarai_kehadiran']);
Route::post('/daftar_manual', [eHadirController::class, 'daftar_manual']);
Route::post('/daftarQR', [eHadirController::class, 'daftarQR']);
Route::post('/tambah_aktiviti', [eHadirController::class, 'tambah_aktiviti']);

Route::post('/getTicketNo', [SmartQv2Controller::class, 'getTicketNo']);

Route::get('/checkstat', [AuthenticationController::class, 'checkstat']);
