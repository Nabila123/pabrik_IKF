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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/* Material Routes  */
Route::post('/material/getSatuan', [App\Http\Controllers\MaterialController::class, 'getSatuan'])->name('material.getSatuan');

/* Admin PO Routes */
Route::get('/adminPO', [App\Http\Controllers\AdminPoController::class, 'index'])->name('adminPO');

Route::get('/adminPO/Order', [App\Http\Controllers\AdminPoController::class, 'poOrder'])->name('adminPO.poOrder');
Route::get('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderCreate'])->name('adminPO.poOrder.create');
Route::post('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderStore'])->name('adminPO.poOrder.create');
Route::get('/adminPO/Order/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetail'])->name('adminPO.poOrder.detail');
Route::get('/adminPO/Order/detail/delete/{detailId}/{purchaseId}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetailDelete'])->name('adminPO.poOrder.detail.delete');
Route::get('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdate'])->name('adminPO.poOrder.update');
Route::post('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdateSave'])->name('adminPO.poOrder.update');
Route::get('/adminPO/Order/unduh/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUnduh'])->name('adminPO.poOrder.unduh');
Route::delete('/adminPO/Order/delete', [App\Http\Controllers\AdminPoController::class, 'poOrderDelete'])->name('adminPO.poOrder.delete');
Route::get('/adminPO/getDetail/{kode}', [App\Http\Controllers\AdminPoController::class, 'getDetail'])->name('adminPO.getDetail');
Route::get('/adminPO/getSuplier/{kode}', [App\Http\Controllers\AdminPoController::class, 'getSuplier'])->name('adminPO.getSuplier');

Route::get('/adminPO/Request', [App\Http\Controllers\AdminPoController::class, 'poRequest'])->name('adminPO.poRequest');
Route::get('/adminPO/Request/tambahData', [App\Http\Controllers\AdminPoController::class, 'poRequestCreate'])->name('adminPO.poRequest.create');
Route::post('/adminPO/Request/tambahData', [App\Http\Controllers\AdminPoController::class, 'poRequestStore'])->name('adminPO.poRequest.create');
Route::get('/adminPO/Request/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestUpdate'])->name('adminPO.poRequest.update');
Route::post('/adminPO/Request/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestUpdateSave'])->name('adminPO.poRequest.update');
Route::get('/adminPO/Request/requestKode/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestRequestKode'])->name('adminPO.poRequest.requestKode');
Route::post('/adminPO/Request/requestKode/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderRequestStore'])->name('adminPO.poRequest.requestKode');
Route::get('/adminPO/Request/approve', [App\Http\Controllers\AdminPoController::class, 'poRequestApprove'])->name('adminPO.poRequest.approve');
Route::get('/adminPO/Request/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestDetail'])->name('adminPO.poRequest.detail');
Route::get('/adminPO/Request/unduh/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestUnduh'])->name('adminPO.poRequest.unduh');
Route::delete('/adminPO/Request/delete', [App\Http\Controllers\AdminPoController::class, 'poRequestDelete'])->name('adminPO.poRequest.delete');

Route::get('/adminPO/LaporanAdminPO', [App\Http\Controllers\AdminPoController::class, 'laporanAdminPO'])->name('adminPO.laporanAdminPO');

/* Gudang Bahan Baku */
Route::get('/bahan_baku', [App\Http\Controllers\GudangBahanBakuController::class, 'index'])->name('bahan_baku');
Route::get('/bahan_baku/supply', [App\Http\Controllers\GudangBahanBakuController::class, 'indexSupplyBarang'])->name('bahan_baku.supply.index');
Route::get('/bahan_baku/supply/create', [App\Http\Controllers\GudangBahanBakuController::class, 'create'])->name('bahan_baku.supply.create');
Route::post('/bahan_baku/supply/create', [App\Http\Controllers\GudangBahanBakuController::class, 'store'])->name('bahan_baku.supply.store');
Route::get('/bahan_baku/suppply/detail/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'detail'])->name('bahan_baku.supply.detail');

Route::get('/bahan_baku/supply/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'edit'])->name('bahan_baku.supply.update');
Route::post('/bahan_baku/update/supply/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'update'])->name('bahan_baku.supply.update');
Route::post('/bahan_baku/supply/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'delete'])->name('bahan_baku.supply.delete');

//GUDANG KELUAR
Route::get('/bahan_baku/keluar', [App\Http\Controllers\GudangBahanBakuController::class, 'keluarGudang'])->name('bahan_baku.keluar');
Route::get('/bahan_baku/keluar/create', [App\Http\Controllers\GudangBahanBakuController::class, 'createKeluarGudang'])->name('bahan_baku.keluar.create');
Route::post('/bahan_baku/keluar/create', [App\Http\Controllers\GudangBahanBakuController::class, 'storeKeluarGudang'])->name('bahan_baku.keluar.store');
Route::get('/bahan_baku/keluar/detail/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'detailKeluarGudang'])->name('bahan_baku.keluar.detail');

Route::get('/bahan_baku/keluar/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'editKeluarGudang'])->name('bahan_baku.keluar.update');
Route::post('/bahan_baku/update/keluar/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'updateKeluarGudang'])->name('bahan_baku.keluar.update');
Route::post('/bahan_baku/keluar/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'deleteKeluarGudang'])->name('bahan_baku.keluar.delete');

//GUDANG MASUK
Route::get('/bahan_baku/masuk', [App\Http\Controllers\GudangBahanBakuController::class, 'masukGudang'])->name('bahan_baku.masuk');
Route::get('/bahan_baku/masuk/create', [App\Http\Controllers\GudangBahanBakuController::class, 'createMasukGudang'])->name('bahan_baku.masuk.create');
Route::post('/bahan_baku/masuk/create', [App\Http\Controllers\GudangBahanBakuController::class, 'storeMasukGudang'])->name('bahan_baku.masuk.store');
Route::get('/bahan_baku/masuk/detail/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'detailKeluarGudang'])->name('bahan_baku.masuk.detail');

Route::get('/bahan_baku/masuk/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'editMasukGudang'])->name('bahan_baku.masuk.update');
Route::post('/bahan_baku/update/masuk/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'updateMasukGudang'])->name('bahan_baku.masuk.update');
Route::post('/bahan_baku/masuk/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'deleteMasukGudang'])->name('bahan_baku.masuk.delete');

/* Gudang Cuci */
Route::get('/gudangCuci', [App\Http\Controllers\GudangCuciController::class, 'index'])->name('GCuci');
Route::get('/gudangCuci/Request', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciRequest'])->name('GCuci.request');
Route::get('/gudangCuci/Request/Terima/{id}', [App\Http\Controllers\GudangCuciController::class, 'RTerimaBarang'])->name('GCuci.request.terima');
Route::get('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rcreate'])->name('GCuci.request.kembali');
Route::post('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rstore'])->name('GCuci.request.kembali');

Route::get('/gudangCuci/Kembali', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciKembali'])->name('GCuci.kembali');


Auth::routes();
