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

/* Material Routes  */
Route::post('/material/getSatuan', [App\Http\Controllers\MaterialController::class, 'getSatuan'])->name('material.getSatuan');

/* Admin PO Routes */
Route::get('/adminPO', [App\Http\Controllers\AdminPoController::class, 'index'])->name('adminPO');
Route::get('/adminPO/Request', [App\Http\Controllers\AdminPoController::class, 'poRequest'])->name('adminPO.poRequest');
Route::get('/adminPO/Request/detail', [App\Http\Controllers\AdminPoController::class, 'poRequestDetail'])->name('adminPO.poRequest.detail');

Route::get('/adminPO/Order', [App\Http\Controllers\AdminPoController::class, 'poOrder'])->name('adminPO.poOrder');
Route::get('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderCreate'])->name('adminPO.poOrder.create');
Route::post('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderStore'])->name('adminPO.poOrder.create');
Route::get('/adminPO/Order/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetail'])->name('adminPO.poOrder.detail');
Route::get('/adminPO/Order/detail/delete/{detailId}/{purchaseId}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetailDelete'])->name('adminPO.poOrder.detail.delete');
Route::get('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdate'])->name('adminPO.poOrder.update');
Route::post('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdateSave'])->name('adminPO.poOrder.update');
Route::delete('/adminPO/Order/delete', [App\Http\Controllers\AdminPoController::class, 'poOrderDelete'])->name('adminPO.poOrder.delete');
Route::get('/adminPO/getDetail/{kode}', [App\Http\Controllers\AdminPoController::class, 'getDetail'])->name('adminPO.getDetail');

Route::get('/adminPO/LaporanAdminPO', [App\Http\Controllers\AdminPoController::class, 'laporanAdminPO'])->name('adminPO.laporanAdminPO');

/* Gudang Bahan Baku */
Route::get('/bahan_baku', [App\Http\Controllers\GudangBahanBakuController::class, 'index'])->name('bahan_baku');
Route::get('/bahan_baku/create', [App\Http\Controllers\GudangBahanBakuController::class, 'create'])->name('bahan_baku.create');
Route::post('/bahan_baku/create', [App\Http\Controllers\GudangBahanBakuController::class, 'store'])->name('bahan_baku.store');
Route::get('/bahan_baku/detail/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'detail'])->name('bahan_baku.detail');

Route::get('/bahan_baku/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'edit'])->name('bahan_baku.update');
Route::post('/bahan_baku/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'update'])->name('bahan_baku.update');
Route::post('/bahan_baku/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'delete'])->name('bahan_baku.delete');




Auth::routes();
