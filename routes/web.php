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
Route::post('/purchase/getData', [App\Http\Controllers\AdminPoController::class, 'getData'])->name('purchase.getData');
Route::post('/purchase/getCheckKode', [App\Http\Controllers\AdminPoController::class, 'getPurchaseKode'])->name('purchase.getCheckKode');
Route::get('/purchase/getDataInvoice', [App\Http\Controllers\AdminPoController::class, 'getDataForInvoice'])->name('purchase.getDataInvoice');

Route::get('/adminPO/Order', [App\Http\Controllers\AdminPoController::class, 'poOrder'])->name('adminPO.poOrder');
Route::get('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderCreate'])->name('adminPO.poOrder.create');
Route::post('/adminPO/Order/tambahData', [App\Http\Controllers\AdminPoController::class, 'poOrderStore'])->name('adminPO.poOrder.create');
Route::get('/adminPO/Order/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetail'])->name('adminPO.poOrder.detail');
Route::get('/adminPO/Order/detail/delete/{detailId}/{purchaseId}', [App\Http\Controllers\AdminPoController::class, 'poOrderDetailDelete'])->name('adminPO.poOrder.detail.delete');
Route::get('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdate'])->name('adminPO.poOrder.update');
Route::post('/adminPO/Order/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUpdateSave'])->name('adminPO.poOrder.update');
Route::get('/adminPO/Order/unduh/{id}', [App\Http\Controllers\AdminPoController::class, 'poOrderUnduh'])->name('adminPO.poOrder.unduh');
Route::delete('/adminPO/Order/delete', [App\Http\Controllers\AdminPoController::class, 'poOrderDelete'])->name('adminPO.poOrder.delete');
Route::get('/adminPO/getDetail/{id}', [App\Http\Controllers\AdminPoController::class, 'getDetail'])->name('adminPO.getDetail');
Route::get('/adminPO/getSuplier/{id}', [App\Http\Controllers\AdminPoController::class, 'getSuplier'])->name('adminPO.getSuplier');

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

Route::get('/adminPO/Invoice', [App\Http\Controllers\AdminPoController::class, 'poInvoice'])->name('adminPO.poInvoice');
Route::get('/adminPO/Invoice/detail/{id}', [App\Http\Controllers\AdminPoController::class, 'poInvoiceDetail'])->name('adminPO.poInvoice.detail');
Route::get('/adminPO/Invoice/tambahData', [App\Http\Controllers\AdminPoController::class, 'poInvoiceCreate'])->name('adminPO.poInvoice.create');
Route::post('/adminPO/Invoice/tambahData', [App\Http\Controllers\AdminPoController::class, 'poInvoiceStore'])->name('adminPO.poInvoice.create');
Route::get('/adminPO/Invoice/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poInvoiceUpdate'])->name('adminPO.poInvoice.update');
Route::post('/adminPO/Invoice/update/{id}', [App\Http\Controllers\AdminPoController::class, 'poInvoiceUpdateSave'])->name('adminPO.poInvoice.update');
Route::delete('/adminPO/Invoice/delete', [App\Http\Controllers\AdminPoController::class, 'poInvoiceDelete'])->name('adminPO.poInvoice.delete');

Route::get('/adminPO/LaporanAdminPO', [App\Http\Controllers\AdminPoController::class, 'laporanAdminPO'])->name('adminPO.laporanAdminPO');

/* Gudang Bahan Baku */
Route::get('/bahan_baku', [App\Http\Controllers\GudangBahanBakuController::class, 'index'])->name('bahan_baku');
Route::get('/bahan_baku/supply', [App\Http\Controllers\GudangBahanBakuController::class, 'indexSupplyBarang'])->name('bahan_baku.supply.index');
Route::get('/bahan_baku/supply/create', [App\Http\Controllers\GudangBahanBakuController::class, 'create'])->name('bahan_baku.supply.create');
Route::post('/bahan_baku/supply/create', [App\Http\Controllers\GudangBahanBakuController::class, 'store'])->name('bahan_baku.supply.store');
Route::get('/bahan_baku/suppply/detail/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'detail'])->name('bahan_baku.supply.detail');

Route::get('/bahan_baku/supply/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'edit'])->name('bahan_baku.supply.update');
Route::post('/bahan_baku/supply/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'update'])->name('bahan_baku.supply.update');
Route::post('/bahan_baku/supply/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'delete'])->name('bahan_baku.supply.delete');
Route::post('/bahan_baku/supply/delDetailMaterial/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'delDetailMaterial'])->name('bahan_baku.supply.delDetailMaterial');

//PPIC Request
Route::get('/bahan_baku/ppicRequest', [App\Http\Controllers\GudangBahanBakuController::class, 'ppicRequest'])->name('bahan_baku.ppicRequest');
Route::get('/bahan_baku/ppicRequest/terima/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'terimaPPICRequest'])->name('bahan_baku.ppicRequest.terima');

//GUDANG KELUAR
Route::get('/bahan_baku/keluar', [App\Http\Controllers\GudangBahanBakuController::class, 'keluarGudang'])->name('bahan_baku.keluar');
Route::get('/bahan_baku/keluar/create', [App\Http\Controllers\GudangBahanBakuController::class, 'createKeluarGudang'])->name('bahan_baku.keluar.create');
Route::get('/bahan_baku/keluar/getMaterial/{gudangRequest}/{jenisKain}', [App\Http\Controllers\GudangBahanBakuController::class, 'getDataMaterial'])->name('bahan_baku.keluar.material');
Route::get('/bahan_baku/keluar/getGudang/{materialId}/{purchaseId}', [App\Http\Controllers\GudangBahanBakuController::class, 'getDataGudang'])->name('bahan_baku.keluar.gudang');
Route::get('/bahan_baku/keluar/getGudangInspeksi/{materialId}/{purchaseId}', [App\Http\Controllers\GudangBahanBakuController::class, 'getDataGudangInspeksi'])->name('bahan_baku.keluar.gudangInspeksi');
Route::get('/bahan_baku/keluar/getDetailMaterial/{materialId}/{purchaseId}/{diameter}/{gramasi}/{berat}', [App\Http\Controllers\GudangBahanBakuController::class, 'getDataDetailMaterial'])->name('bahan_baku.keluar.detailMaterial');
Route::get('/bahan_baku/keluar/getDetailMaterialInspeksi/{materialId}/{purchaseId}/{diameter}/{gramasi}/{berat}', [App\Http\Controllers\GudangBahanBakuController::class, 'getDataDetailMaterialInspeksi'])->name('bahan_baku.keluar.detailMaterialInspeksi');
Route::post('/bahan_baku/keluar/create', [App\Http\Controllers\GudangBahanBakuController::class, 'storeKeluarGudang'])->name('bahan_baku.keluar.store');
Route::get('/bahan_baku/keluar/detail/{id}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'detailKeluarGudang'])->name('bahan_baku.keluar.detail');
Route::get('/bahan_baku/keluar/detail/delete/{gudangId}/{detailId}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'deleteDetailGudang'])->name('bahan_baku.keluar.detail.delete');
Route::get('/bahan_baku/keluar/update/{id}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'updateKeluarGudang'])->name('bahan_baku.keluar.update');
Route::post('/bahan_baku/keluar/update/{id}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'updateSaveKeluarGudang'])->name('bahan_baku.keluar.update');

// Route::get('/bahan_baku/keluar/update/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'editKeluarGudang'])->name('bahan_baku.keluar.update');
// Route::post('/bahan_baku/update/keluar/{id}', [App\Http\Controllers\GudangBahanBakuController::class, 'updateKeluarGudang'])->name('bahan_baku.keluar.update');
Route::post('/bahan_baku/keluar/delete', [App\Http\Controllers\GudangBahanBakuController::class, 'deleteKeluarGudang'])->name('bahan_baku.keluar.delete');

//GUDANG MASUK
Route::get('/bahan_baku/masuk', [App\Http\Controllers\GudangBahanBakuController::class, 'masukGudang'])->name('bahan_baku.masuk');
Route::get('/bahan_baku/masuk/detail/{id}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'detailKeluarGudang'])->name('bahan_baku.masuk.detail');
Route::get('/bahan_baku/masuk/terima/{id}/{gudangRequest}', [App\Http\Controllers\GudangBahanBakuController::class, 'terimaMasukGudang'])->name('bahan_baku.masuk.terima');


/* Gudang Rajut */
Route::get('/gudangRajut', [App\Http\Controllers\GudangRajutController::class, 'index'])->name('GRajut');
Route::get('/gudangRajut/Request', [App\Http\Controllers\GudangRajutController::class, 'gudangRajutRequest'])->name('GRajut.request');
Route::get('/gudangRajut/Request/Detail/{id}', [App\Http\Controllers\GudangRajutController::class, 'RDetail'])->name('GRajut.request.detail');
Route::get('/gudangRajut/Request/Terima/{id}', [App\Http\Controllers\GudangRajutController::class, 'RTerimaBarang'])->name('GRajut.request.terima');
Route::get('/gudangRajut/Request/Kembali/{id}', [App\Http\Controllers\GudangRajutController::class, 'Rcreate'])->name('GRajut.request.kembali');
Route::post('/gudangRajut/Request/Kembali/{id}', [App\Http\Controllers\GudangRajutController::class, 'Rstore'])->name('GRajut.request.kembali');

Route::get('/gudangRajut/Kembali', [App\Http\Controllers\GudangRajutController::class, 'gudangRajutKembali'])->name('GRajut.kembali');
Route::get('/gudangRajut/Kembali/Detail/{id}', [App\Http\Controllers\GudangRajutController::class, 'KDetail'])->name('GRajut.kembali.detail');
Route::get('/gudangRajut/Kembali/update/{id}', [App\Http\Controllers\GudangRajutController::class, 'RUpdate'])->name('GRajut.kembali.update');
Route::post('/gudangRajut/Kembali/update/{id}', [App\Http\Controllers\GudangRajutController::class, 'RUpdateSave'])->name('GRajut.kembali.update');
Route::get('/gudangRajut/Kembali/update/delete/{gdRajutMId}/{gdRajutMDId}', [App\Http\Controllers\GudangRajutController::class, 'RUpdateDelete'])->name('GRajut.kembali.update.delete');
Route::delete('/gudangRajut/Kembali/delete', [App\Http\Controllers\GudangRajutController::class, 'RDelete'])->name('GRajut.kembali.delete');


/* Gudang Cuci */
Route::get('/gudangCuci', [App\Http\Controllers\GudangCuciController::class, 'index'])->name('GCuci');
Route::get('/gudangCuci/Request', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciRequest'])->name('GCuci.request');
Route::get('/gudangCuci/Request/Detail/{id}', [App\Http\Controllers\GudangCuciController::class, 'RDetail'])->name('GCuci.request.detail');
Route::get('/gudangCuci/Request/Terima/{id}', [App\Http\Controllers\GudangCuciController::class, 'RTerimaBarang'])->name('GCuci.request.terima');
Route::get('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rcreate'])->name('GCuci.request.kembali');
Route::post('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rstore'])->name('GCuci.request.kembali');

Route::get('/gudangCuci/Kembali', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciKembali'])->name('GCuci.kembali');
Route::get('/gudangCuci/Kembali/Detail/{id}', [App\Http\Controllers\GudangCuciController::class, 'KDetail'])->name('GCuci.kembali.detail');
Route::delete('/gudangCuci/Kembali/delete', [App\Http\Controllers\GudangCuciController::class, 'KDelete'])->name('GCuci.kembali.delete');


/* Gudang Compact */
Route::get('/gudangCompact', [App\Http\Controllers\GudangCompactController::class, 'index'])->name('GCompact');
Route::get('/gudangCompact/Request', [App\Http\Controllers\GudangCompactController::class, 'gudangCompactRequest'])->name('GCompact.request');
Route::get('/gudangCompact/Request/Detail/{id}', [App\Http\Controllers\GudangCompactController::class, 'RDetail'])->name('GCompact.request.detail');
Route::get('/gudangCompact/Request/Terima/{id}', [App\Http\Controllers\GudangCompactController::class, 'RTerimaBarang'])->name('GCompact.request.terima');
Route::get('/gudangCompact/Request/Kembali/{id}', [App\Http\Controllers\GudangCompactController::class, 'Rcreate'])->name('GCompact.request.kembali');
Route::post('/gudangCompact/Request/Kembali/{id}', [App\Http\Controllers\GudangCompactController::class, 'Rstore'])->name('GCompact.request.kembali');

Route::get('/gudangCompact/Kembali', [App\Http\Controllers\GudangCompactController::class, 'gudangCompactKembali'])->name('GCompact.kembali');
Route::get('/gudangCompact/Kembali/Detail/{id}', [App\Http\Controllers\GudangCompactController::class, 'KDetail'])->name('GCompact.kembali.detail');
Route::delete('/gudangCompact/Kembali/delete', [App\Http\Controllers\GudangCompactController::class, 'KDelete'])->name('GCompact.kembali.delete');


/* Gudang Inspeksi */
Route::get('/gudangInspeksi', [App\Http\Controllers\GudangInspeksiController::class, 'index'])->name('GInspeksi');

Route::get('/gudangInspeksi/Request', [App\Http\Controllers\GudangInspeksiController::class, 'gudangInspeksiRequest'])->name('GInspeksi.request');
Route::get('/gudangInspeksi/Request/Detail/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'RDetail'])->name('GInspeksi.request.detail');
Route::get('/gudangInspeksi/Request/Terima/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'RTerimaBarang'])->name('GInspeksi.request.terima');
Route::get('/gudangInspeksi/Request/Kembali/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'Rcreate'])->name('GInspeksi.request.kembali');
Route::post('/gudangInspeksi/Request/Kembali/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'Rstore'])->name('GInspeksi.request.kembali');


Route::get('/gudangInspeksi/Kembali', [App\Http\Controllers\GudangInspeksiController::class, 'gudangInspeksiKembali'])->name('GInspeksi.kembali');
Route::get('/gudangInspeksi/Kembali/Detail/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'KDetail'])->name('GInspeksi.kembali.detail');
Route::delete('/gudangInspeksi/Kembali/delete', [App\Http\Controllers\GudangInspeksiController::class, 'KDelete'])->name('GInspeksi.kembali.delete');

Route::get('/gudangInspeksi/proses', [App\Http\Controllers\GudangInspeksiController::class, 'gudangInspeksiproses'])->name('GInspeksi.proses');
Route::get('/gudangInspeksi/proses/create', [App\Http\Controllers\GudangInspeksiController::class, 'PCreate'])->name('GInspeksi.proses.create');
Route::post('/gudangInspeksi/proses/create', [App\Http\Controllers\GudangInspeksiController::class, 'PStore'])->name('GInspeksi.proses.create');
Route::get('/gudangInspeksi/proses/getDetailMaterial/{purchaseId}/{materialId}/{diameter}/{gramasi}', [App\Http\Controllers\GudangInspeksiController::class, 'getDataDetailMaterial'])->name('GInspeksi.proses.detailMaterial');
Route::get('/gudangInspeksi/proses/detail/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'PDetail'])->name('GInspeksi.proses.detail');
Route::get('/gudangInspeksi/proses/update/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'PUpdate'])->name('GInspeksi.proses.update');
Route::post('/gudangInspeksi/proses/update/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'PUpdateInspeksi'])->name('GInspeksi.proses.update');

Route::get('/gudangInspeksi/proses/update/delete/{detailId}/{inspeksiId}', [App\Http\Controllers\GudangInspeksiController::class, 'PUpdateDelete'])->name('GInspeksi.proses.update.delete');
Route::delete('/gudangInspeksi/proses/delete', [App\Http\Controllers\GudangInspeksiController::class, 'PDelete'])->name('GInspeksi.proses.delete');

/* PPIC */
Route::get('/ppic', [App\Http\Controllers\PPICController::class, 'index'])->name('ppic');

Route::get('/ppic/Gudang', [App\Http\Controllers\PPICController::class, 'gdRequest'])->name('ppic.gdRequest');
Route::get('/ppic/Gudang/Create', [App\Http\Controllers\PPICController::class, 'gdRequestCreate'])->name('ppic.gdRequest.create');
Route::post('/ppic/Gudang/Create', [App\Http\Controllers\PPICController::class, 'gdRequestStore'])->name('ppic.gdRequest.create');
Route::get('ppic/Gudang/detail/{id}', [App\Http\Controllers\PPICController::class, 'gdRequestDetail'])->name('ppic.gdRequest.detail');
Route::get('/ppic/Gudang/detail/delete/{detailId}/{ppicRequestId}', [App\Http\Controllers\PPICController::class, 'gdRequestDetailDelete'])->name('adminPO.poOrder.detail.delete');
Route::get('/ppic/Gudang/Update/{id}', [App\Http\Controllers\PPICController::class, 'gdRequestUpdate'])->name('ppic.gdRequest.update');
Route::post('/ppic/Gudang/Update/{id}', [App\Http\Controllers\PPICController::class, 'gdRequestUpdateStore'])->name('ppic.gdRequest.update');
Route::delete('/ppic/Gudang/delete', [App\Http\Controllers\PPICController::class, 'gdRequestDelete'])->name('ppic.gdRequest.delete');

/* Gudang Potong */
Route::get('/GPotong', [App\Http\Controllers\GudangPotongController::class, 'index'])->name('GPotong');
Route::post('/GPotong/getData', [App\Http\Controllers\GudangPotongController::class, 'getData'])->name('GPotong.getData');

Route::get('/GPotong/request', [App\Http\Controllers\GudangPotongController::class, 'gRequest'])->name('GPotong.request');
Route::get('/GPotong/request/detail/{id}', [App\Http\Controllers\GudangPotongController::class, 'gReqDetail'])->name('GPotong.request.detail');
Route::get('/GPotong/request/terima/{id}', [App\Http\Controllers\GudangPotongController::class, 'gReqTerima'])->name('GPotong.request.terima');

Route::get('/GPotong/keluar', [App\Http\Controllers\GudangPotongController::class, 'gKeluar'])->name('GPotong.keluar');
Route::get('/GPotong/keluar/detail/{id}', [App\Http\Controllers\GudangPotongController::class, 'gKeluarDetail'])->name('GPotong.keluar.detail');
Route::get('/GPotong/keluar/Terima/{id}', [App\Http\Controllers\GudangPotongController::class, 'gKeluarTerima'])->name('GPotong.keluar.terima');
Route::get('/GPotong/keluar/kembali/{id}', [App\Http\Controllers\GudangPotongController::class, 'gKeluarKembali'])->name('GPotong.keluar.kembali');
Route::Post('/GPotong/keluar/kembali/{id}', [App\Http\Controllers\GudangPotongController::class, 'gKeluarKembaliStore'])->name('GPotong.keluar.kembali');

Route::get('/GPotong/proses', [App\Http\Controllers\GudangPotongController::class, 'gProses'])->name('GPotong.proses');
Route::get('/GPotong/proses/create', [App\Http\Controllers\GudangPotongController::class, 'gProsesCreate'])->name('GPotong.proses.create');
Route::post('/GPotong/proses/create', [App\Http\Controllers\GudangPotongController::class, 'gProsesStore'])->name('GPotong.proses.create');
Route::get('/GPotong/proses/getDetailMaterial/{purchaseId}/{materialId}/{diameter}/{gramasi}', [App\Http\Controllers\GudangPotongController::class, 'getDataDetailMaterial'])->name('GPotong.proses.detailMaterial');
Route::get('/GPotong/proses/detail/{id}', [App\Http\Controllers\GudangPotongController::class, 'gProsesDetail'])->name('GPotong.proses.detail');
Route::get('GPotong/proses/update/{id}', [App\Http\Controllers\GudangPotongController::class, 'gProsesUpdate'])->name('GPotong.proses.update');
Route::post('GPotong/proses/update/{id}', [App\Http\Controllers\GudangPotongController::class, 'gProsesUpdatePotong'])->name('GPotong.proses.update');
Route::get('GPotong/proses/update/delete/{detailId}/{inspeksiId}', [App\Http\Controllers\GudangPotongController::class, 'gProsesUpdateDelete'])->name('GPotong.proses.update.delete');
Route::delete('/GPotong/proses/delete', [App\Http\Controllers\GudangPotongController::class, 'gProsesDelete'])->name('GPotong.proses.delete');

/* Gudang Jahit */
Route::get('/GJahit', [App\Http\Controllers\GudangJahitController::class, 'index'])->name('GJahit');
Route::post('/GJahit/getData', [App\Http\Controllers\GudangJahitController::class, 'getData'])->name('GJahit.getData');
Route::post('/GJahit/getBasis', [App\Http\Controllers\GudangJahitController::class, 'getBasis'])->name('GJahit.getBasis');
Route::post('/GJahit/getPegawai', [App\Http\Controllers\GudangJahitController::class, 'getPegawai'])->name('GJahit.getPegawai');

Route::get('/GJahit/request', [App\Http\Controllers\GudangJahitController::class, 'gRequest'])->name('GJahit.request');
Route::get('/GJahit/request/Terima/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRequestTerima'])->name('GJahit.request.terima');
Route::get('/GJahit/request/detail/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRequestDetail'])->name('GJahit.request.detail');

Route::get('/GJahit/operator', [App\Http\Controllers\GudangJahitController::class, 'gOperator'])->name('GJahit.operator');
Route::get('/GJahit/operator/detail/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorDetail'])->name('GJahit.operator.detail');
Route::get('/GJahit/operator/create', [App\Http\Controllers\GudangJahitController::class, 'gOperatorCreate'])->name('GJahit.operator.create');
Route::post('/GJahit/operator/create', [App\Http\Controllers\GudangJahitController::class, 'gOperatorStore'])->name('GJahit.operator.create');
Route::get('/GJahit/operator/getDetailMaterial', [App\Http\Controllers\GudangJahitController::class, 'gOperatorDataMaterial'])->name('GJahit.operator.detailMaterial');
Route::get('/GJahit/operator/update/{id}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorUpdate'])->name('GJahit.operator.update');
Route::post('/GJahit/operator/update/{id}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorUpdateSave'])->name('GJahit.operator.update');
Route::get('/GJahit/operator/update/delete/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorUpdateDelete'])->name('GJahit.operator.update.delete');
Route::delete('/GJahit/operator/delete', [App\Http\Controllers\GudangJahitController::class, 'gOperatorDelete'])->name('GJahit.operator.delete');

Route::get('/GJahit/basis/create', [App\Http\Controllers\GudangJahitController::class, 'gBasisCreate'])->name('GJahit.basis.create');
Route::post('/GJahit/basis/create', [App\Http\Controllers\GudangJahitController::class, 'gBasisStore'])->name('GJahit.basis.create');
Route::get('/GJahit/basis/detail/{posisi}', [App\Http\Controllers\GudangJahitController::class, 'gBasisDetail'])->name('GJahit.basis.detail');
Route::get('/GJahit/basis/update/{posisi}', [App\Http\Controllers\GudangJahitController::class, 'gBasisUpdate'])->name('GJahit.basis.update');
Route::post('/GJahit/basis/update/{posisi}', [App\Http\Controllers\GudangJahitController::class, 'gBasisUpdateSave'])->name('GJahit.basis.update');
Route::delete('/GJahit/basis/delete', [App\Http\Controllers\GudangJahitController::class, 'gBasisDelete'])->name('GJahit.basis.delete');

Route::get('/GJahit/rekap/create', [App\Http\Controllers\GudangJahitController::class, 'gRekapCreate'])->name('GJahit.rekap.create');
Route::post('/GJahit/rekap/create', [App\Http\Controllers\GudangJahitController::class, 'gRekapStore'])->name('GJahit.rekap.create');
Route::get('/GJahit/rekap/detail/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRekapDetail'])->name('GJahit.rekap.detail');
Route::get('/GJahit/rekap/update/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRekapUpdate'])->name('GJahit.rekap.update');
Route::post('/GJahit/rekap/update/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRekapUpdateSave'])->name('GJahit.rekap.update');
Route::get('/GJahit/rekap/update/delete/{rekapId}/{pegawaiId}/{purchaseId}/{jenisBaju}/{ukuranBaju}/{posisi}', [App\Http\Controllers\GudangJahitController::class, 'gRekapUpdateDelete'])->name('GJahit.rekap.update.delete');

Route::get('/GJahit/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangJahitController::class, 'gKeluarCreate'])->name('GJahit.keluar.create');
Route::post('/GJahit/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangJahitController::class, 'gKeluarStore'])->name('GJahit.keluar.create');
Route::get('/GJahit/keluar/detail/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangJahitController::class, 'gKeluarDetail'])->name('GJahit.keluar.detail');

Route::get('/GJahit/reject', [App\Http\Controllers\GudangJahitController::class, 'gReject'])->name('GJahit.reject');
Route::get('/GJahit/reject/Terima/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRejectTerima'])->name('GJahit.reject.terima');
Route::get('/GJahit/reject/Kembali/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRejectKembali'])->name('GJahit.reject.kembali');
Route::get('/GJahit/reject/detail/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRejectDetail'])->name('GJahit.reject.detail');

/* Gudang Batil */
Route::get('/GBatil', [App\Http\Controllers\GudangBatilController::class, 'index'])->name('GBatil');
Route::post('/GBatil/getData', [App\Http\Controllers\GudangBatilController::class, 'getData'])->name('GBatil.getData');
Route::post('/GBatil/getBasis', [App\Http\Controllers\GudangBatilController::class, 'getBasis'])->name('GBatil.getBasis');
Route::post('/GBatil/getPegawai', [App\Http\Controllers\GudangBatilController::class, 'getPegawai'])->name('GBatil.getPegawai');
Route::post('/GBatil/getReject', [App\Http\Controllers\GudangBatilController::class, 'getReject'])->name('GBatil.getReject');

Route::get('/GBatil/request', [App\Http\Controllers\GudangBatilController::class, 'gRequest'])->name('GBatil.request');
Route::get('/GBatil/request/terima/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRequestTerima'])->name('GBatil.request.terima');
Route::get('/GBatil/request/detail/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRequestDetail'])->name('GBatil.request.detail');

Route::get('/GBatil/operator', [App\Http\Controllers\GudangBatilController::class, 'gOperator'])->name('GBatil.operator');
Route::get('/GBatil/operator/detail/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangBatilController::class, 'gOperatorDetail'])->name('GBatil.operator.detail');
Route::get('/GBatil/operator/create', [App\Http\Controllers\GudangBatilController::class, 'gOperatorCreate'])->name('GBatil.operator.create');
Route::post('/GBatil/operator/create', [App\Http\Controllers\GudangBatilController::class, 'gOperatorStore'])->name('GBatil.operator.create');
Route::get('/GBatil/operator/getDetailMaterial/{purchaseId}/{jenisBaju}/{ukuranBaku}/{jumlahBaju}', [App\Http\Controllers\GudangBatilController::class, 'gOperatorDataMaterial'])->name('GBatil.operator.detailMaterial');
Route::get('/GBatil/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangBatilController::class, 'gOperatorupdate'])->name('GBatil.operator.update');
Route::post('/GBatil/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangBatilController::class, 'gOperatorupdateSave'])->name('GBatil.operator.update');
Route::get('/GBatil/operator/update/delete/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangBatilController::class, 'gOperatorUpdateDelete'])->name('GBatil.operator.update.delete');
Route::delete('/GBatil/operator/delete', [App\Http\Controllers\GudangBatilController::class, 'gOperatorDelete'])->name('GBatil.operator.delete');

Route::get('/GBatil/rekap/create', [App\Http\Controllers\GudangBatilController::class, 'gRekapCreate'])->name('GBatil.rekap.create');
Route::post('/GBatil/rekap/create', [App\Http\Controllers\GudangBatilController::class, 'gRekapStore'])->name('GBatil.rekap.create');
Route::get('/GBatil/rekap/detail/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRekapDetail'])->name('GBatil.rekap.detail');
Route::get('/GBatil/rekap/update/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRekapUpdate'])->name('GBatil.rekap.update');
Route::post('/GBatil/rekap/update/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRekapUpdateSave'])->name('GBatil.rekap.update');
Route::get('/GBatil/rekap/update/delete/{rekapId}/{pegawaiId}/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangBatilController::class, 'gRekapUpdateDelete'])->name('GBatil.rekap.update.delete');

Route::get('/GBatil/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangBatilController::class, 'gKeluarCreate'])->name('GBatil.keluar.create');
Route::post('/GBatil/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangBatilController::class, 'gKeluarStore'])->name('GBatil.keluar.create');
Route::get('/GBatil/keluar/detail/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangBatilController::class, 'gKeluarDetail'])->name('GBatil.keluar.detail');

Route::get('/GBatil/reject', [App\Http\Controllers\GudangBatilController::class, 'gReject'])->name('GBatil.reject');
Route::get('/GBatil/reject/Terima/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRejectTerima'])->name('GBatil.reject.terima');
Route::get('/GBatil/reject/create', [App\Http\Controllers\GudangBatilController::class, 'gRejectCreate'])->name('GBatil.reject.create');
Route::post('/GBatil/reject/create', [App\Http\Controllers\GudangBatilController::class, 'gRejectStore'])->name('GBatil.reject.create');
Route::get('/GBatil/reject/detail/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRejectDetail'])->name('GBatil.reject.detail');
Route::get('/GBatil/reject/update/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRejectUpdate'])->name('GBatil.reject.update');
Route::post('/GBatil/reject/update/{id}', [App\Http\Controllers\GudangBatilController::class, 'gRejectUpdateSave'])->name('GBatil.reject.update');
Route::get('/GBatil/reject/update/delete/{rejectId}/{detailRejectId}', [App\Http\Controllers\GudangBatilController::class, 'gRejectUpdateDelete'])->name('GBatil.reject.update.delete');
Route::delete('/GBatil/reject/delete', [App\Http\Controllers\GudangBatilController::class, 'gRejectDelete'])->name('GBatil.reject.delete');

/* Gudang Control */
Route::get('/GControl', [App\Http\Controllers\GudangControlController::class, 'index'])->name('GControl');
Route::post('/GControl/getData', [App\Http\Controllers\GudangControlController::class, 'getData'])->name('GControl.getData');
Route::post('/GControl/getBasis', [App\Http\Controllers\GudangControlController::class, 'getBasis'])->name('GControl.getBasis');
Route::post('/GControl/getPegawai', [App\Http\Controllers\GudangControlController::class, 'getPegawai'])->name('GControl.getPegawai');
Route::post('/GControl/getReject', [App\Http\Controllers\GudangControlController::class, 'getReject'])->name('GControl.getReject');

Route::get('/GControl/request', [App\Http\Controllers\GudangControlController::class, 'gRequest'])->name('GControl.request');
Route::get('/GControl/request/terima/{id}', [App\Http\Controllers\GudangControlController::class, 'gRequestTerima'])->name('GControl.request.terima');
Route::get('/GControl/request/detail/{id}', [App\Http\Controllers\GudangControlController::class, 'gRequestDetail'])->name('GControl.request.detail');

Route::get('/GControl/operator', [App\Http\Controllers\GudangControlController::class, 'gOperator'])->name('GControl.operator');
Route::get('/GControl/operator/detail/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangControlController::class, 'gOperatorDetail'])->name('GControl.operator.detail');
Route::get('/GControl/operator/create', [App\Http\Controllers\GudangControlController::class, 'gOperatorCreate'])->name('GControl.operator.create');
Route::post('/GControl/operator/create', [App\Http\Controllers\GudangControlController::class, 'gOperatorStore'])->name('GControl.operator.create');
Route::get('/GControl/operator/getDetailMaterial/{purchaseId}/{jenisBaju}/{ukuranBaku}/{jumlahBaju}', [App\Http\Controllers\GudangControlController::class, 'gOperatorDataMaterial'])->name('GControl.operator.detailMaterial');
Route::get('/GControl/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangControlController::class, 'gOperatorupdate'])->name('GControl.operator.update');
Route::post('/GControl/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangControlController::class, 'gOperatorupdateSave'])->name('GControl.operator.update');
Route::get('/GControl/operator/update/delete/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangControlController::class, 'gOperatorUpdateDelete'])->name('GControl.operator.update.delete');
Route::delete('/GControl/operator/delete', [App\Http\Controllers\GudangControlController::class, 'gOperatorDelete'])->name('GControl.operator.delete');

Route::get('/GControl/rekap/create', [App\Http\Controllers\GudangControlController::class, 'gRekapCreate'])->name('GControl.rekap.create');
Route::post('/GControl/rekap/create', [App\Http\Controllers\GudangControlController::class, 'gRekapStore'])->name('GControl.rekap.create');
Route::get('/GControl/rekap/detail/{id}', [App\Http\Controllers\GudangControlController::class, 'gRekapDetail'])->name('GControl.rekap.detail');
Route::get('/GControl/rekap/update/{id}', [App\Http\Controllers\GudangControlController::class, 'gRekapUpdate'])->name('GControl.rekap.update');
Route::post('/GControl/rekap/update/{id}', [App\Http\Controllers\GudangControlController::class, 'gRekapUpdateSave'])->name('GControl.rekap.update');
Route::get('/GControl/rekap/update/delete/{rekapId}/{pegawaiId}/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangControlController::class, 'gRekapUpdateDelete'])->name('GControl.rekap.update.delete');

Route::get('/GControl/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangControlController::class, 'gKeluarCreate'])->name('GControl.keluar.create');
Route::post('/GControl/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangControlController::class, 'gKeluarStore'])->name('GControl.keluar.create');
Route::get('/GControl/keluar/detail/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangControlController::class, 'gKeluarDetail'])->name('GControl.keluar.detail');

Route::get('/GControl/reject', [App\Http\Controllers\GudangControlController::class, 'gReject'])->name('GControl.reject');
Route::get('/GControl/reject/Terima/{id}', [App\Http\Controllers\GudangControlController::class, 'gRejectTerima'])->name('GControl.reject.terima');
Route::get('/GControl/reject/TJTerima/{id}', [App\Http\Controllers\GudangControlController::class, 'gRejectTJTerima'])->name('GControl.reject.TJterima');
Route::get('/GControl/reject/Kembali/{id}', [App\Http\Controllers\GudangControlController::class, 'gRejectKembali'])->name('GControl.reject.kembali');

Route::get('/GControl/reject/create', [App\Http\Controllers\GudangControlController::class, 'gRejectTJCreate'])->name('GControl.reject.create');
Route::post('/GControl/reject/create', [App\Http\Controllers\GudangControlController::class, 'gRejectTJStore'])->name('GControl.reject.create');
Route::get('/GControl/reject/detail/{id}/{request}', [App\Http\Controllers\GudangControlController::class, 'gRejectTJDetail'])->name('GControl.reject.detail');
Route::get('/GControl/reject/update/{id}', [App\Http\Controllers\GudangControlController::class, 'gRejectTJUpdate'])->name('GControl.reject.update');
Route::post('/GControl/reject/update/{id}', [App\Http\Controllers\GudangControlController::class, 'gRejectTJUpdateSave'])->name('GControl.reject.update');
Route::get('/GControl/reject/update/delete/{rejectId}/{detailRejectId}', [App\Http\Controllers\GudangControlController::class, 'gRejectTJUpdateDelete'])->name('GControl.reject.update.delete');
Route::delete('/GControl/reject/delete', [App\Http\Controllers\GudangControlController::class, 'gRejectTJDelete'])->name('GControl.reject.delete');

//Packing
Route::get('/GPacking', [App\Http\Controllers\PackingController::class, 'index'])->name('GPacking');
Route::post('/GPacking/getPegawai', [App\Http\Controllers\PackingController::class, 'getPegawai'])->name('GPacking.getPegawai');
Route::post('/GPacking/getReject', [App\Http\Controllers\PackingController::class, 'getReject'])->name('GPacking.getReject');
Route::get('/GPacking/getKodePacking/{kodePacking}', [App\Http\Controllers\PackingController::class, 'getKodePacking'])->name('GPacking.getKodePacking');
Route::post('/GPacking/getKode', [App\Http\Controllers\PackingController::class, 'getKode'])->name('GPacking.getKode');

Route::get('/GPacking/request', [App\Http\Controllers\PackingController::class, 'gRequest'])->name('GPacking.request');
Route::get('/GPacking/request/terima/{id}', [App\Http\Controllers\PackingController::class, 'gRequestTerima'])->name('GPacking.request.terima');
Route::get('/GPacking/request/detail/{id}', [App\Http\Controllers\PackingController::class, 'gRequestDetail'])->name('GPacking.request.detail');

Route::get('/GPacking/operator', [App\Http\Controllers\PackingController::class, 'gOperator'])->name('GPacking.operator');
Route::get('/GPacking/rekap/detail/{id}', [App\Http\Controllers\PackingController::class, 'gRekapDetail'])->name('GPacking.rekap.detail');
Route::get('/GPacking/rekap/create', [App\Http\Controllers\PackingController::class, 'gRekapCreate'])->name('GPacking.rekap.create');
Route::post('/GPacking/rekap/create', [App\Http\Controllers\PackingController::class, 'gRekapStore'])->name('GPacking.rekap.create');
Route::get('/GPacking/rekap/update/{id}', [App\Http\Controllers\PackingController::class, 'gRekapUpdate'])->name('GPacking.rekap.update');
Route::post('/GPacking/rekap/update/{id}', [App\Http\Controllers\PackingController::class, 'gRekapUpdateSave'])->name('GPacking.rekap.update');
Route::get('/GPacking/rekap/update/delete/{rekapId}/{rekapDetailId}', [App\Http\Controllers\PackingController::class, 'gRekapUpdateDelete'])->name('GPacking.rekap.update.delete');
Route::get('/GPacking/rekap/cetakBarcode', [App\Http\Controllers\PackingController::class, 'gRekapCetakBarcode'])->name('GPacking.rekap.cetakBarcode');

Route::get('/GPacking/bahanBaku/create', [App\Http\Controllers\PackingController::class, 'gPackingBahanBakuCreate'])->name('GPacking.bahanBaku.create');
Route::post('/GPacking/bahanBaku/create', [App\Http\Controllers\PackingController::class, 'gPackingBahanBakuStore'])->name('GPacking.bahanBaku.create');
Route::delete('/GPacking/bahanBaku/delete', [App\Http\Controllers\PackingController::class, 'gPackingBahanBakuDelete'])->name('GPacking.bahanBaku.delete');

Route::get('/GPacking/reject', [App\Http\Controllers\PackingController::class, 'gReject'])->name('GPacking.reject');
Route::get('/GPacking/reject/Terima/{id}', [App\Http\Controllers\PackingController::class, 'gRejectTerima'])->name('GPacking.reject.terima');
Route::get('/GPacking/reject/create', [App\Http\Controllers\PackingController::class, 'gRejectCreate'])->name('GPacking.reject.create');
Route::post('/GPacking/reject/create', [App\Http\Controllers\PackingController::class, 'gRejectStore'])->name('GPacking.reject.create');
Route::get('/GPacking/reject/detail/{id}', [App\Http\Controllers\PackingController::class, 'gRejectDetail'])->name('GPacking.reject.detail');
Route::get('/GPacking/reject/update/{id}', [App\Http\Controllers\PackingController::class, 'gRejectUpdate'])->name('GPacking.reject.update');
Route::post('/GPacking/reject/update/{id}', [App\Http\Controllers\PackingController::class, 'gRejectUpdateSave'])->name('GPacking.reject.update');
Route::get('/GPacking/reject/update/delete/{rejectId}/{detailRejectId}', [App\Http\Controllers\PackingController::class, 'gRejectUpdateDelete'])->name('GPacking.reject.update.delete');
Route::delete('/GPacking/reject/delete', [App\Http\Controllers\PackingController::class, 'gRejectDelete'])->name('GPacking.reject.delete');

//Setrika
Route::get('/GSetrika', [App\Http\Controllers\GudangSetrikaController::class, 'index'])->name('GSetrika');
Route::post('/GSetrika/getData', [App\Http\Controllers\GudangSetrikaController::class, 'getData'])->name('GSetrika.getData');
Route::post('/GSetrika/getBasis', [App\Http\Controllers\GudangSetrikaController::class, 'getBasis'])->name('GSetrika.getBasis');
Route::post('/GSetrika/getPegawai', [App\Http\Controllers\GudangSetrikaController::class, 'getPegawai'])->name('GSetrika.getPegawai');
Route::post('/GSetrika/getReject', [App\Http\Controllers\GudangSetrikaController::class, 'getReject'])->name('GSetrika.getReject');

Route::get('/GSetrika/request', [App\Http\Controllers\GudangSetrikaController::class, 'gRequest'])->name('GSetrika.request');
Route::get('/GSetrika/request/terima/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRequestTerima'])->name('GSetrika.request.terima');
Route::get('/GSetrika/request/detail/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRequestDetail'])->name('GSetrika.request.detail');

Route::get('/GSetrika/operator', [App\Http\Controllers\GudangSetrikaController::class, 'gOperator'])->name('GSetrika.operator');
Route::get('/GSetrika/operator/detail/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorDetail'])->name('GSetrika.operator.detail');
Route::get('/GSetrika/operator/create', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorCreate'])->name('GSetrika.operator.create');
Route::post('/GSetrika/operator/create', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorStore'])->name('GSetrika.operator.create');
Route::get('/GSetrika/operator/getDetailMaterial/{purchaseId}/{jenisBaju}/{ukuranBaku}/{jumlahBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorDataMaterial'])->name('GSetrika.operator.detailMaterial');
Route::get('/GSetrika/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorupdate'])->name('GSetrika.operator.update');
Route::post('/GSetrika/operator/update/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorupdateSave'])->name('GSetrika.operator.update');
Route::get('/GSetrika/operator/update/delete/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorUpdateDelete'])->name('GSetrika.operator.update.delete');
Route::delete('/GSetrika/operator/delete', [App\Http\Controllers\GudangSetrikaController::class, 'gOperatorDelete'])->name('GSetrika.operator.delete');

Route::get('/GSetrika/rekap/create', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapCreate'])->name('GSetrika.rekap.create');
Route::post('/GSetrika/rekap/create', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapStore'])->name('GSetrika.rekap.create');
Route::get('/GSetrika/rekap/detail/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapDetail'])->name('GSetrika.rekap.detail');
Route::get('/GSetrika/rekap/update/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapUpdate'])->name('GSetrika.rekap.update');
Route::post('/GSetrika/rekap/update/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapUpdateSave'])->name('GSetrika.rekap.update');
Route::get('/GSetrika/rekap/update/delete/{rekapId}/{pegawaiId}/{purchaseId}/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangSetrikaController::class, 'gRekapUpdateDelete'])->name('GSetrika.rekap.update.delete');

Route::get('/GSetrika/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangSetrikaController::class, 'gKeluarCreate'])->name('GSetrika.keluar.create');
Route::post('/GSetrika/keluar/create/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangSetrikaController::class, 'gKeluarStore'])->name('GSetrika.keluar.create');
Route::get('/GSetrika/keluar/detail/{jenisBaju}/{ukuranBaju}/{date}', [App\Http\Controllers\GudangSetrikaController::class, 'gKeluarDetail'])->name('GSetrika.keluar.detail');

Route::get('/GSetrika/reject', [App\Http\Controllers\GudangSetrikaController::class, 'gReject'])->name('GSetrika.reject');
Route::get('/GSetrika/reject/Terima/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectTerima'])->name('GSetrika.reject.terima');
Route::get('/GSetrika/reject/create', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectCreate'])->name('GSetrika.reject.create');
Route::post('/GSetrika/reject/create', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectStore'])->name('GSetrika.reject.create');
Route::get('/GSetrika/reject/detail/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectDetail'])->name('GSetrika.reject.detail');
Route::get('/GSetrika/reject/update/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectUpdate'])->name('GSetrika.reject.update');
Route::post('/GSetrika/reject/update/{id}', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectUpdateSave'])->name('GSetrika.reject.update');
Route::get('/GSetrika/reject/update/delete/{rejectId}/{detailRejectId}', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectUpdateDelete'])->name('GSetrika.reject.update.delete');
Route::delete('/GSetrika/reject/delete', [App\Http\Controllers\GudangSetrikaController::class, 'gRejectDelete'])->name('GSetrika.reject.delete');

//MATERIAL
Route::get('/material', [App\Http\Controllers\MaterialController::class, 'index'])->name('Material');
Route::get('/material/create', [App\Http\Controllers\MaterialController::class, 'create'])->name('Material.create');
Route::post('material/create', [App\Http\Controllers\MaterialController::class, 'store'])->name('Material.store');
Route::get('/material/edit/{id}', [App\Http\Controllers\MaterialController::class, 'edit'])->name('Material.edit');
Route::post('material/edit/{id}', [App\Http\Controllers\MaterialController::class, 'update'])->name('Material.update');
Route::get('material/detail/{id}', [App\Http\Controllers\MaterialController::class, 'detail'])->name('Material.detail');
Route::delete('material/delete', [App\Http\Controllers\MaterialController::class, 'delete'])->name('Material.delete');

//Gudang Barang Jadi
Route::get('/GBarangJadi', [App\Http\Controllers\GudangBarangJadiController::class, 'index'])->name('GBarangJadi');
Route::post('/GBarangJadi/getBarangJadi', [App\Http\Controllers\GudangBarangJadiController::class, 'getBarangJadi'])->name('GBarangJadi.getBarangJadi');

Route::get('/GBarangJadi/operator', [App\Http\Controllers\GudangBarangJadiController::class, 'gOperator'])->name('GBarangJadi.operator');
Route::get('/GBarangJadi/operator/create', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorcreate'])->name('GBarangJadi.operator.create');
Route::post('/GBarangJadi/operator/create', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorstore'])->name('GBarangJadi.operator.store');
Route::get('/GBarangJadi/operator/detail/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorDetail'])->name('GBarangJadi.operator.detail');
Route::get('/GBarangJadi/operator/update/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorupdate'])->name('GBarangJadi.operator.update');
Route::post('/GBarangJadi/operator/update/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorUpdateSave'])->name('GBarangJadi.operator.updateSave');
Route::get('/GBarangJadi/operator/update/delete/{penjualanId}/{penjualanDetailId}/{jenisBaju}/{ukuranBaju}/{qty}', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorUpdateDelete'])->name('GBarangJadi.operator.update.delete');
Route::delete('/GBarangJadi/operator/delete', [App\Http\Controllers\GudangBarangJadiController::class, 'goperatorDelete'])->name('GBarangJadi.operator.delete');

Route::get('/GBarangJadi/requestPotong', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotong'])->name('GBarangJadi.requestPotong');
Route::get('/GBarangJadi/requestPotong/detail/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongDetail'])->name('GBarangJadi.requestPotong.detail');
Route::get('/GBarangJadi/requestPotong/create', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongcreate'])->name('GBarangJadi.requestPotong.create');
Route::post('/GBarangJadi/requestPotong/create', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongstore'])->name('GBarangJadi.requestPotong.store');
Route::get('/GBarangJadi/requestPotong/update/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongupdate'])->name('GBarangJadi.requestPotong.update');
Route::post('/GBarangJadi/requestPotong/update/{id}', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongUpdateSave'])->name('GBarangJadi.requestPotong.updateSave');
Route::get('/GBarangJadi/requestPotong/update/delete/{requestId}/{detailRequestId}', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongUpdateDelete'])->name('GBarangJadi.requestPotong.update.delete');
Route::delete('/GBarangJadi/requestPotong/delete', [App\Http\Controllers\GudangBarangJadiController::class, 'gRequestPotongDelete'])->name('GBarangJadi.requestPotong.delete');

//Keuangan
Route::get('/Keuangan', [App\Http\Controllers\KeuanganController::class, 'index'])->name('Keuangan');
Route::post('/Keuangan/getPenjualan', [App\Http\Controllers\KeuanganController::class, 'getPenjualan'])->name('Keuangan.getPenjualan');

Route::get('/Keuangan/purchaseOrder', [App\Http\Controllers\KeuanganController::class, 'purchaseOrder'])->name('Keuangan.purchaseOrder');

Route::get('/Keuangan/purchaseInvoice', [App\Http\Controllers\KeuanganController::class, 'purchaseInvoice'])->name('Keuangan.purchaseInvoice');

Route::get('/Keuangan/penjualan', [App\Http\Controllers\KeuanganController::class, 'penjualan'])->name('Keuangan.penjualan');
Route::get('/Keuangan/penjualan/create', [App\Http\Controllers\KeuanganController::class, 'penjualanCreate'])->name('Keuangan.penjualan.create');
Route::post('/Keuangan/penjualan/create', [App\Http\Controllers\KeuanganController::class, 'penjualanStore'])->name('Keuangan.penjualan.create');
Route::get('/Keuangan/penjualan/detail/{kodeTransaksi}', [App\Http\Controllers\KeuanganController::class, 'penjualanDetail'])->name('Keuangan.penjualan.detail');
Route::delete('/Keuangan/penjualan/delete', [App\Http\Controllers\KeuanganController::class, 'penjualanDelete'])->name('Keuangan.penjualan.delete');

Auth::routes();
