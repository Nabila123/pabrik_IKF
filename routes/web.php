<?php

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* Admin PO Routes */
Route::get('/adminPO', [App\Http\Controllers\AdminPoController::class, 'index'])->name('adminPO');
Route::get('/adminPO/Request', [App\Http\Controllers\AdminPoController::class, 'poRequest'])->name('adminPO.poRequest');
Route::get('/adminPO/Request/detail', [App\Http\Controllers\AdminPoController::class, 'poRequestDetail'])->name('adminPO.poRequest.detail');

Route::get('/adminPO/Order', [App\Http\Controllers\AdminPoController::class, 'poOrder'])->name('adminPO.poOrder');
Route::get('/adminPO/Order/detail', [App\Http\Controllers\AdminPoController::class, 'poOrderDetail'])->name('adminPO.poOrder.detail');

Route::get('/adminPO/LaporanAdminPO', [App\Http\Controllers\AdminPoController::class, 'laporanAdminPO'])->name('adminPO.laporanAdminPO');


Auth::routes();
