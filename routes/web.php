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

Route::get('/adminPO/Order', [App\Http\Controllers\AdminPoController::class, 'poOrder'])->name('adminPO.poOrder');
Route::get('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderCreate'])->name('adminPO.poOrder.create');
Route::post('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderStore'])->name('adminPO.poOrder.create');
Route::get('/adminPO/Order/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetail'])->name('adminPO.poOrder.detail');
Route::get('/adminPO/Order/detail/delete/{detailId}/{purchaseId}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetailDelete'])->name('adminPO.poOrder.detail.delete');
Route::get('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdate'])->name('adminPO.poOrder.update');
Route::post('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdateSave'])->name('adminPO.poOrder.update');
Route::delete('/adminPO/Order/delete', [App\Http\Controllers\AdminPoController::class, 'poOrderDelete'])->name('adminPO.poOrder.delete');

Route::get('/adminPO/Request', [App\Http\Controllers\AdminPoController::class, 'poRequest'])->name('adminPO.poRequest');
Route::get('/adminPO/Request/requestKode/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestRequestKode'])->name('adminPO.poRequest.requestKode');
Route::post('/adminPO/Request/requestKode/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderRequestStore'])->name('adminPO.poRequest.requestKode');
Route::get('/adminPO/Request/approve', [App\Http\Controllers\AdminPoController::class, 'poRequestApprove'])->name('adminPO.poRequest.approve');
Route::get('/adminPO/Request/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poRequestDetail'])->name('adminPO.poRequest.detail');

Route::get('/adminPO/LaporanAdminPO', [App\Http\Controllers\AdminPoController::class, 'laporanAdminPO'])->name('adminPO.laporanAdminPO');



Auth::routes();
