<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', function () {
    return redirect('login');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::group(['middleware' => ['role:hrd']], function () {
        Route::resource('roles', 'RoleController');
        Route::resource('pengguna', 'UserController');
        Route::get('/data/pengguna', 'UserController@getData');
        Route::resource('master-cuti', 'MasterCutiController');
        Route::get('/data/master-cuti', 'MasterCutiController@getData');
        Route::get('/data/notifikasi-hrd', 'NotifikasiController@hrd');
        Route::get('approval/{id}', 'PengajuanCutiController@approval')->name('approval');
        Route::post('pengajuan/{id}/approve', 'PengajuanCutiController@setApproval')->name('approve');
    });
    Route::get('/data/notifikasi-karyawan', 'NotifikasiController@karyawan');

    Route::resource('pengajuan', 'PengajuanCutiController');
    Route::get('/data/pengajuan', 'PengajuanCutiController@getData');
});
