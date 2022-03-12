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


/* Gudang Cuci */
Route::get('/gudangCuci', [App\Http\Controllers\GudangCuciController::class, 'index'])->name('GCuci');
Route::get('/gudangCuci/Request', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciRequest'])->name('GCuci.request');
Route::get('/gudangCuci/Request/Detail/{id}', [App\Http\Controllers\GudangCuciController::class, 'RDetail'])->name('GCuci.request.detail');
Route::get('/gudangCuci/Request/Terima/{id}', [App\Http\Controllers\GudangCuciController::class, 'RTerimaBarang'])->name('GCuci.request.terima');
Route::get('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rcreate'])->name('GCuci.request.kembali');
Route::post('/gudangCuci/Request/Kembali/{id}', [App\Http\Controllers\GudangCuciController::class, 'Rstore'])->name('GCuci.request.kembali');

Route::get('/gudangCuci/Kembali', [App\Http\Controllers\GudangCuciController::class, 'gudangCuciKembali'])->name('GCuci.kembali');
Route::get('/gudangCuci/Kembali/Detail/{id}', [App\Http\Controllers\GudangCuciController::class, 'KDetail'])->name('GCuci.kembali.detail');


/* Gudang Compact */
Route::get('/gudangCompact', [App\Http\Controllers\GudangCompactController::class, 'index'])->name('GCompact');
Route::get('/gudangCompact/Request', [App\Http\Controllers\GudangCompactController::class, 'gudangCompactRequest'])->name('GCompact.request');
Route::get('/gudangCompact/Request/Detail/{id}', [App\Http\Controllers\GudangCompactController::class, 'RDetail'])->name('GCompact.request.detail');
Route::get('/gudangCompact/Request/Terima/{id}', [App\Http\Controllers\GudangCompactController::class, 'RTerimaBarang'])->name('GCompact.request.terima');
Route::get('/gudangCompact/Request/Kembali/{id}', [App\Http\Controllers\GudangCompactController::class, 'Rcreate'])->name('GCompact.request.kembali');
Route::post('/gudangCompact/Request/Kembali/{id}', [App\Http\Controllers\GudangCompactController::class, 'Rstore'])->name('GCompact.request.kembali');

Route::get('/gudangCompact/Kembali', [App\Http\Controllers\GudangCompactController::class, 'gudangCompactKembali'])->name('GCompact.kembali');
Route::get('/gudangCompact/Kembali/Detail/{id}', [App\Http\Controllers\GudangCompactController::class, 'KDetail'])->name('GCompact.kembali.detail');


/* Gudang Inspeksi */
Route::get('/gudangInspeksi', [App\Http\Controllers\GudangInspeksiController::class, 'index'])->name('GInspeksi');

Route::get('/gudangInspeksi/Request', [App\Http\Controllers\GudangInspeksiController::class, 'gudangInspeksiRequest'])->name('GInspeksi.request');
Route::get('/gudangInspeksi/Request/Detail/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'RDetail'])->name('GInspeksi.request.detail');
Route::get('/gudangInspeksi/Request/Terima/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'RTerimaBarang'])->name('GInspeksi.request.terima');
Route::get('/gudangInspeksi/Request/Kembali/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'Rcreate'])->name('GInspeksi.request.kembali');
Route::post('/gudangInspeksi/Request/Kembali/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'Rstore'])->name('GInspeksi.request.kembali');


Route::get('/gudangInspeksi/Kembali', [App\Http\Controllers\GudangInspeksiController::class, 'gudangInspeksiKembali'])->name('GInspeksi.kembali');
Route::get('/gudangInspeksi/Kembali/Detail/{id}', [App\Http\Controllers\GudangInspeksiController::class, 'KDetail'])->name('GInspeksi.kembali.detail');

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

Route::get('/GJahit/request', [App\Http\Controllers\GudangJahitController::class, 'gRequest'])->name('GJahit.request');
Route::get('/GJahit/request/Terima/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRequestTerima'])->name('GJahit.request.terima');
Route::get('/GJahit/request/detail/{id}', [App\Http\Controllers\GudangJahitController::class, 'gRequestDetail'])->name('GJahit.request.detail');

Route::get('/GJahit/operator', [App\Http\Controllers\GudangJahitController::class, 'gOperator'])->name('GJahit.operator');
Route::get('/GJahit/operator/detail/{jenisBaju}/{ukuranBaju}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorDetail'])->name('GJahit.operator.detail');
Route::get('/GJahit/operator/create', [App\Http\Controllers\GudangJahitController::class, 'gOperatorCreate'])->name('GJahit.operator.create');
Route::post('/GJahit/operator/create', [App\Http\Controllers\GudangJahitController::class, 'gOperatorStore'])->name('GJahit.operator.create');
Route::get('/GJahit/operator/getDetailMaterial/{purchaseId}/{jenisBaju}/{ukuranBaku}', [App\Http\Controllers\GudangJahitController::class, 'gOperatorDataMaterial'])->name('GJahit.operator.detailMaterial');
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

Route::get('/GJahit/reject', [App\Http\Controllers\GudangJahitController::class, 'gReject'])->name('GJahit.reject');

Auth::routes();
