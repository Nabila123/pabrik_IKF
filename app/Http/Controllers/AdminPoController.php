<?php

namespace App\Http\Controllers;
use App\Models\AdminPurchase;
use App\Models\MaterialModel;
use App\Models\AdminPurchaseDetail;
use App\Models\adminPurchaseInvoice;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\BarangDatang;
use App\Models\BarangDatangDetail;
use App\Models\BarangDatangDetailMaterial;
use DB, PDF;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

class AdminPoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //PURCHASE ORDER FUNCTION
    public function index()
    {
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();
        $poRequest = AdminPurchase::where('jenisPurchase', 'Purchase Request')->get();

        $poOrder = count($poOrder);
        $poRequest = count($poRequest);

        return view('adminPO.index', ['order' => $poOrder, 'request' => $poRequest]);
    }

    public function getData(Request $request)
    {
        $purchase = AdminPurchase::getPurchaseWithMaterial($request);

        return json_encode($purchase);
    }

    public function getDataForInvoice(Request $request)
    {
        $getData = AdminPurchase::getDataInvoice($request->purchaseId);

        return json_encode($getData);
    }

    public function getPurchaseKode(Request $request)
    {
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')
            ->where('kode', $request->purchaseKode)
            ->first();
        if ($purchase != null) {
            return 0;
        }
        return 1;
    }

    public function poOrder()
    {
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();

        foreach ($poOrder as $order) {
            $cekDatang = GudangBahanBaku::select('id', 'created_at')
                ->where('purchaseId', $order->id)
                ->first();

            if ($cekDatang != null) {
                $order['barangDatang'] = true;
                $order['barangDatangAt'] = $cekDatang->created_at;
            }
        }

        return view('adminPO.purchaseOrder.index', ['poOrder' => $poOrder]);
    }

    public function poOrderCreate()
    {
        $materials = MaterialModel::get();
        $purchaseKode = AdminPurchase::purchaseKode();
        $getpurchaseKode = AdminPurchase::where('jenisPurchase', 'Purchase Order')
            ->where('kode', $purchaseKode)
            ->get();
        while (empty($getpurchaseKode)) {
            $purchaseKode = AdminPurchase::purchaseKode();
        }

        return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode]);
    }

    public function poOrderStore(Request $request)
    {
        DB::beginTransaction();

        try {
            $purchaseKode = $request['purchaseKode'];
            $suplierName = $request['suplierName'];
            $jenisPurchase = 'Purchase Order';
            $pengajuanDate = !empty($request['pengajuanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengajuanDate'])) : null;
            $pengirimanDate = !empty($request['pengirimanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])) : null;
            $jatuhTempoDate = !empty($request['jatuhTempoDate']) ? date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])) : null;
            $notePesan = $request['notePesan'];

            $material = $request['material'];
            $jumlah = $request['jumlah'];
            $satuan = $request['satuan'];
            $harga = $request['harga'];
            $totalHarga = $request['totalHarga'];
            $note = $request['note'];

            $jumlahData = $request['jumlah_data'];

            if ($jumlahData != 0) {
                $total = 0;
                for ($i = 0; $i < $jumlahData; $i++) {
                    $total += $totalHarga[$i];
                }

                $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);

                if ($purchaseCreate) {
                    $purchaseId = $purchaseCreate;

                    for ($i = 0; $i < $jumlahData; $i++) {
                        AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $material[$i], $jumlah[$i], $satuan[$i], $harga[$i], $totalHarga[$i], $note[$i]);
                    }

                    DB::commit();

                    return redirect('adminPO/Order')->with('success', 'Data Berhasil Disimpan');
                }
            } else {
                $materials = MaterialModel::get();
                $purchaseKode = AdminPurchase::purchaseKode();
                $getpurchaseKode = AdminPurchase::where('kode', $purchaseKode)->get();
                while (empty($getpurchaseKode)) {
                    $purchaseKode = AdminPurchase::purchaseKode();
                }
                return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode, 'message' => 'Material Belum Diisi']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Silahkan Cek Kembali Data Anda');
            }
        }
    }

    public function poOrderDetail($id)
    {
        $getPurchaseId = AdminPurchase::where('id', $id)
            ->where('jenisPurchase', 'Purchase Order')
            ->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();

        $gudangDatang = [];
        $barang = BarangDatang::select('id')
            ->where('purchaseId', $getPurchaseId->id)
            ->get();
        if (count($barang) != 0) {
            foreach ($barang as $Gbarang) {
                $barangDetail = BarangDatangDetail::where('barangDatangId', $Gbarang->id)->get();
                if (count($barangDetail) != 0) {
                    foreach ($barangDetail as $detail) {
                        $barangDetailMaterial = BarangDatangDetailMaterial::where('barangDatangDetailId', $detail->id)->get();
                        foreach ($barangDetailMaterial as $detailMaterial) {
                            if (count($gudangDatang) != 0 && $detail->materialId == 1) {
                                for ($i = 0; $i < count($gudangDatang) - 1; $i++) {
                                    if ($detail->materialId == $gudangDatang[$i]['materialId']) {
                                        $gudangDatang[$i]['brutto'] += $detailMaterial->brutto;
                                        $gudangDatang[$i]['netto'] += $detailMaterial->netto;
                                        $gudangDatang[$i]['tarra'] += $detailMaterial->tarra;
                                    }
                                }
                            } else {
                                $gudangDatang['datang'] = true;
                                $gudangDatang[] = [
                                    'materialId' => $detail->materialId,
                                    'diameter' => $detailMaterial->diameter == 0 ? '-' : $detailMaterial->diameter,
                                    'gramasi' => $detailMaterial->gramasi == 0 ? '-' : $detailMaterial->gramasi,
                                    'brutto' => $detailMaterial->brutto,
                                    'netto' => $detailMaterial->netto,
                                    'tarra' => $detailMaterial->tarra,
                                ];
                            }
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < count($gudangDatang) - 1; $i++) {
            if ($gudangDatang[$i]['materialId'] == 1) {
                $netto = $gudangDatang[$i]['netto'] / 181.44;
                $gudangDatang[$i]['netto'] = (int) $netto;
            }
        }
        // dd($gudangDatang);
        return view('adminPO.purchaseOrder.detail', ['purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId, 'datang' => $gudangDatang]);
    }

    public function getDetail($id)
    {
        $getPurchase = AdminPurchase::find($id);
        $getPurchaseDetail = AdminPurchaseDetail::where('purchaseId', $id)->get();
        $cekBarangDatangDetail = BarangDatangDetail::where('purchaseId', $id)->first();
        foreach ($getPurchaseDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
            if ($cekBarangDatangDetail) {
                $barangDatangDetail = BarangDatangDetail::where('purchaseId', $id)
                    ->where('materialId', $value->materialId)
                    ->sum('jumlah_datang');
                $value->qtySaatIni = $cekBarangDatangDetail ? $barangDatangDetail : 0;
            } else {
                $value->qtySaatIni = 0;
            }
        }

        return json_encode($getPurchaseDetail);
    }

    public function getSuplier($id)
    {
        $getPurchase = AdminPurchase::where('id', $id)
            ->where('jenisPurchase', 'Purchase Order')
            ->first();
        return json_encode($getPurchase->suplierName);
    }

    public function poOrderUpdate($id)
    {
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('id', $id)
            ->where('jenisPurchase', 'Purchase Order')
            ->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();

        return view('adminPO.purchaseOrder.update', ['materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poOrderUpdateSave(Request $request)
    {
        $purchaseid = $request['purchaseId'];
        $purchaseKode = $request['purchaseKode'];
        $jenisPurchase = 'Purchase Order';
        $pengajuanDate = !empty($request['pengajuanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengajuanDate'])) : null;
        $pengirimanDate = !empty($request['pengirimanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])) : null;
        $jatuhTempoDate = !empty($request['jatuhTempoDate']) ? date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])) : null;
        $notePesan = $request['notePesan'];
        $total = (int) $request['total'];

        $material = $request['material'];
        $jumlah = $request['jumlah'];
        $satuan = $request['satuan'];
        $harga = $request['harga'];
        $totalHarga = $request['totalHarga'];
        $note = $request['note'];

        $jumlahData = $request['jumlah_data'];

        if ($jumlahData != 0) {
            for ($i = 0; $i < $jumlahData; $i++) {
                $total += $totalHarga[$i];
            }

            $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);

            if ($purchaseUpdate == 1) {
                for ($i = 0; $i < $jumlahData; $i++) {
                    AdminPurchaseDetail::purchaseDetailCreate($purchaseid, $material[$i], $jumlah[$i], $satuan[$i], $harga[$i], $totalHarga[$i], $note[$i]);
                }
                return redirect('adminPO/Order');
            }
        } else {
            $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
            return redirect('adminPO/Order');
        }
    }

    public function poOrderUnduh($id)
    {
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')
            ->where('id', $id)
            ->first();
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $id)->get();

        $pdf = PDF::loadview('adminPO.purchaseOrder.unduh', ['purchase' => $purchase, 'purchaseDetail' => $purchaseDetail])->setPaper('a5', 'potrait');
        return $pdf->stream();
    }

    public function poOrderDetailDelete($detailId, $purchaseId)
    {
        DB::beginTransaction();
        try {
            $purchaseDetail = AdminPurchaseDetail::where('id', $detailId)->delete();
            if ($purchaseDetail) {
                DB::commit();
                return redirect('adminPO/Request/update/' . $purchaseId . '');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Silahkan Cek Kembali Data Anda');
            }
        }
    }

    public function poOrderDelete(Request $request)
    {
        DB::beginTransaction();

        try {
            AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();
            AdminPurchase::where('id', $request['purchaseId'])->delete();

            DB::commit();

            return redirect('adminPO/Order')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                dd($th);
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Data Gagal Dihapus');
            }
        }
    }
    //END PURCHASE ORDER FUNCTION

    //PURCHASE REQUEST FUNCTION
    public function poRequest()
    {
        $poRequest = AdminPurchase::where('jenisPurchase', 'Purchase Request')->get();
        foreach ($poRequest as $request) {
            $cek = AdminPurchase::where('jenisPurchase', 'Purchase Order')
                ->where('kode', $request->kode)
                ->first();
            if ($cek != null) {
                $cekDatang = BarangDatang::where('purchaseId', $cek->id)->first();
                if ($cekDatang != null) {
                    $request['barangDatang'] = true;
                    $request['barangDatangAt'] = $cekDatang->created_at;
                }

                $request['prosesOrder'] = true;
                $request['prosesOrderAt'] = $cek->tanggal;
            }
        }

        return view('adminPO.purchaseRequest.index', ['poRequest' => $poRequest]);
    }

    public function poRequestCreate()
    {
        $materials = MaterialModel::get();

        return view('adminPO.purchaseRequest.create', ['materials' => $materials]);
    }

    public function poRequestStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchaseKode = '-';
            $suplierName = isset($request['suplierName']) ? $request['suplierName'] : null;
            $jenisPurchase = 'Purchase Request';
            $pengajuanDate = !empty($request['pengajuanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengajuanDate'])) : null;
            $pengirimanDate = !empty($request['pengirimanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])) : null;
            $jatuhTempoDate = !empty($request['jatuhTempoDate']) ? date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])) : null;
            $notePesan = $request['notePesan'];

            $material = $request['material'];
            $jumlah = $request['jumlah'];
            $satuan = $request['satuan'];
            // $harga = $request['harga'];
            // $totalHarga = $request['totalHarga'];
            $note = $request['note'];

            $jumlahData = $request['jumlah_data'];

            if ($jumlahData != 0) {
                $total = 0;
                // for ($i=0; $i < $jumlahData; $i++) {
                //     $total += $totalHarga[$i];
                // }

                $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);

                if ($purchaseCreate) {
                    $purchaseId = $purchaseCreate;

                    for ($i = 0; $i < $jumlahData; $i++) {
                        AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $material[$i], $jumlah[$i], $satuan[$i], 0, 0, $note[$i]);
                    }

                    DB::commit();
                    return redirect('adminPO/Request')->with('success', 'Data Berhasil Disimpan');
                }
            } else {
                $materials = MaterialModel::get();
                return view('adminPO.purchaseRequest.create', ['materials' => $materials, 'message' => 'Material Belum Diisi']);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Silahkan Cek Kembali Data Anda');
            }
        }
    }

    public function poRequestUpdate($id)
    {
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('id', $id)
            ->where('jenisPurchase', 'Purchase Request')
            ->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        return view('adminPO.purchaseRequest.update', ['materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poRequestUpdateSave(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchaseid = $request['purchaseId'];
            $purchaseKode = $request['purchaseKode'];
            $jenisPurchase = 'Purchase Request';
            $pengajuanDate = !empty($request['pengajuanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengajuanDate'])) : null;
            $pengirimanDate = !empty($request['pengirimanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])) : null;
            $jatuhTempoDate = !empty($request['jatuhTempoDate']) ? date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])) : null;
            $notePesan = $request['notePesan'];
            $total = (int) $request['total'];

            $material = $request['material'];
            $jumlah = $request['jumlah'];
            $satuan = $request['satuan'];
            // $harga = $request['harga'];
            // $totalHarga = $request['totalHarga'];
            $note = $request['note'];

            $jumlahData = $request['jumlah_data'];

            if ($jumlahData != 0) {
                // for ($i=0; $i < $jumlahData; $i++) {
                //     $total += $totalHarga[$i];
                // }

                $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);

                if ($purchaseUpdate == 1) {
                    for ($i = 0; $i < $jumlahData; $i++) {
                        AdminPurchaseDetail::purchaseDetailCreate($purchaseid, $material[$i], $jumlah[$i], $satuan[$i], 0, 0, $note[$i]);
                    }

                    DB::commit();
                    return redirect('adminPO/Request')->with('success', 'Data Berhasil Diupdate');
                }
            } else {
                $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
                return redirect('adminPO/Request')->with('error', 'Data Tidak Boleh Kosong');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect('adminPO/Request')->with('error', 'Data Gagal Diupdate');
            }
        }
    }

    public function poRequestRequestKode($purchaseId)
    {
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('jenisPurchase', 'Purchase Request')
            ->where('id', $purchaseId)
            ->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $getPurchaseId->id)->get();
        $jenisPurchase = 'Request';

        return view('adminPO.purchaseOrder.create', ['jenisPurchase' => $jenisPurchase, 'materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poOrderRequestStore(Request $request)
    {
        // dd($request);
        $purchaseid = $request['purchaseId'];
        $purchaseKode = $request['purchaseKode'];
        $suplierName = $request['suplierName'];
        $jenisPurchase = 'Purchase Order';
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = !empty($request['pengirimanDate']) ? date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])) : null;
        $jatuhTempoDate = !empty($request['jatuhTempoDate']) ? date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])) : null;
        $notePesan = $request['notePesan'];

        $harga = $request['harga'];
        $totalHarga = $request['totalHarga'];
        $total = (int) $request['total'];

        $details = AdminPurchaseDetail::where('purchaseId', $purchaseid)->get();

        foreach ($details as $key => $value) {
            if ($totalHarga[$value->id] != null) {
                $total += (int) $totalHarga[$value->id];
            }
        }

        $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
        if ($purchaseCreate) {
            AdminPurchase::purchaseUpdateField('kode', $purchaseKode, $purchaseid);
        }
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $purchaseid)->get();

        $purchaseId = $purchaseCreate;
        // dd($purchaseDetail);
        foreach ($purchaseDetail as $detail) {
            AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $detail->materialId, $detail->qty, $detail->unit, $harga[$detail->id], $totalHarga[$detail->id], $detail->remark);
        }

        return redirect('adminPO/Order');
    }

    public function poRequestApprove(Request $request)
    {
        $Approved = AdminPurchase::purchaseUpdateField($request['approve'], \Auth::user()->roleId, $request['purchaseId']);
        if ($Approved == 1) {
            $ApprovedAt = AdminPurchase::purchaseUpdateField($request['approveAt'], date('Y-m-d H:i:s'), $request['purchaseId']);

            if ($ApprovedAt == 1) {
                return 1;
            }
        }
    }

    public function poRequestDetail($id)
    {
        $getPurchaseRequest = AdminPurchase::where('id', $id)
            ->where('jenisPurchase', 'Purchase Request')
            ->first();
        $getPurchaseId = AdminPurchase::where('kode', $getPurchaseRequest->kode)
            ->where('jenisPurchase', 'Purchase Order')
            ->first();
        if ($getPurchaseId != null) {
            $getPurchaseRequest->suplierName = $getPurchaseId->suplierName;
        } else {
            $getPurchaseRequest->suplierName = '-';
        }
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();

        $gudangDatang = [];
        $barang = BarangDatang::select('id')
            ->where('purchaseId', $id)
            ->get();
        if (count($barang) != 0) {
            foreach ($barang as $Gbarang) {
                $barangDetail = BarangDatangDetail::where('barangDatangId', $Gbarang->id)->get();
                if (count($barangDetail) != 0) {
                    foreach ($barangDetail as $detail) {
                        $barangDetailMaterial = BarangDatangDetailMaterial::where('barangDatangDetailId', $detail->id)->get();
                        foreach ($barangDetailMaterial as $detailMaterial) {
                            if (count($gudangDatang) != 0 && $detail->materialId == 1) {
                                for ($i = 0; $i < count($gudangDatang) - 1; $i++) {
                                    if ($detail->materialId == $gudangDatang[$i]['materialId']) {
                                        $gudangDatang[$i]['brutto'] += $detailMaterial->brutto;
                                        $gudangDatang[$i]['netto'] += $detailMaterial->netto;
                                        $gudangDatang[$i]['tarra'] += $detailMaterial->tarra;
                                    }
                                }
                            } else {
                                $gudangDatang['datang'] = true;
                                $gudangDatang[] = [
                                    'materialId' => $detail->materialId,
                                    'diameter' => $detailMaterial->diameter == 0 ? '-' : $detailMaterial->diameter,
                                    'gramasi' => $detailMaterial->gramasi == 0 ? '-' : $detailMaterial->gramasi,
                                    'brutto' => $detailMaterial->brutto,
                                    'netto' => $detailMaterial->netto,
                                    'tarra' => $detailMaterial->tarra,
                                ];
                            }
                        }
                    }
                }
            }
        }

        for ($i = 0; $i < count($gudangDatang) - 1; $i++) {
            if ($gudangDatang[$i]['materialId'] == 1) {
                $netto = $gudangDatang[$i]['netto'] / 181.44;
                $gudangDatang[$i]['netto'] = (int) $netto;
            }
        }
        return view('adminPO.purchaseRequest.detail', ['request' => $getPurchaseRequest, 'requestDetail' => $getPurchaseDetailId, 'datang' => $gudangDatang]);
    }

    public function poRequestUnduh($id)
    {
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Request')
            ->where('id', $id)
            ->first();
        $getPurchaseId = AdminPurchase::where('kode', $purchase->kode)
            ->where('jenisPurchase', 'Purchase Order')
            ->first();
        if ($getPurchaseId != null) {
            $purchase->suplierName = $getPurchaseId->suplierName;
        } else {
            $purchase->suplierName = '-';
        }
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $id)->get();

        $pdf = PDF::loadview('adminPO.purchaseRequest.unduh', ['purchase' => $purchase, 'purchaseDetail' => $purchaseDetail])->setPaper('a5', 'potrait');
        return $pdf->stream();
    }

    public function poRequestDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();
            AdminPurchase::where('id', $request['purchaseId'])->delete();

            DB::commit();
            return redirect('adminPO/Request')->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            DB::rollback();
            if (env('APP_ENV') == 'local') {
                throw $th;
            } else {
                return redirect('adminPO/Request')->with('error', 'Data Gagal Dihapus');
            }
        }
    }
    //END PURCHASE REQUEST FUNCTION

    //START PURCHASE INVOICE FUNCTION
    public function poInvoice()
    {
        $invoice = adminPurchaseInvoice::all();
        return view('adminPO.purchaseInvoice.index', ['invoice' => $invoice]);
    }

    public function poInvoiceDetail($id)
    {
        $data = [];
        $i = 0;
        $invoice = adminPurchaseInvoice::where('id', $id)->first();
        $gudang = BarangDatang::where('purchaseId', $invoice->purchaseId)->first();
        $gudangDetail = BarangDatangDetail::where('barangDatangId', $gudang->id)->get();
        foreach ($gudangDetail as $detail) {
            $gudangMaterial = BarangDatangDetailMaterial::where('barangDatangDetailId', $detail->id)->get();
            foreach ($gudangMaterial as $material) {
                $data['material'][$i++] = [
                    'nama' => $detail->material->nama,
                    'diameter' => $material->diameter,
                    'gramasi' => $material->gramasi,
                    'brutto' => $material->brutto,
                    'netto' => $material->netto,
                    'tarra' => $material->tarra,
                    'satuan' => $material->unit,
                    'harga' => $material->unitPrice,
                    'note' => $material->remark,
                ];
            }
        }

        return view('adminPO.purchaseInvoice.detail', ['invoice' => $invoice, 'material' => $data]);
    }

    public function poInvoiceCreate()
    {
        $purchaseId = BarangDatang::where('total', 0)->get();
        $i = 1;
        $temp = '';
        foreach ($purchaseId as $value) {
            if ($value->purchaseId == $temp) {
                $value->kedatangan = 'Kedatangan Ke - ' . $i++;
            } else {
                $i = 1;
                $checkPurchase = BarangDatang::where('purchaseId', $value->purchaseId)
                    ->where('total', '!=', 0)
                    ->get();
                if (count($checkPurchase) != 0) {
                    $i += count($checkPurchase);
                }

                $value->kedatangan = 'Kedatangan Ke - ' . $i++;
            }

            $temp = $value->purchaseId;
        }

        return view('adminPO.purchaseInvoice.create', ['purchaseId' => $purchaseId]);
    }

    public function poInvoiceStore(Request $request)
    {
        $invoice = adminPurchaseInvoice::createInvoicePurchase($request);
        if ($invoice == 1) {
            $gudangUpdate = BarangDatang::updateFieldGudang('Total', $request['total'], $request['barangDatangId']);
        }

        $invoice = adminPurchaseInvoice::all();
        return view('adminPO.purchaseInvoice.index', ['invoice' => $invoice]);
    }

    public function poInvoiceUpdate($id)
    {
        $invoice = adminPurchaseInvoice::where('id', $id)->first();
        $barangDatangId = BarangDatang::where('id', $invoice->gudangId)->first();

        return view('adminPO.purchaseInvoice.update', ['invoice' => $invoice, 'purchase' => $barangDatangId]);
    }

    public function poInvoiceUpdateSave(Request $request)
    {
        $invoice = adminPurchaseInvoice::updateInvoicePurchase($request);
        if ($invoice == 1) {
            $gudangUpdate = BarangDatang::updateFieldGudang('Total', $request['total'], $request['barangDatangId']);
        }

        $invoice = adminPurchaseInvoice::all();
        return view('adminPO.purchaseInvoice.index', ['invoice' => $invoice]);
    }

    public function poInvoiceDelete(Request $request)
    {
        $invoice = adminPurchaseInvoice::where('id', $request['invoiceId'])->first();
        if ($invoice) {
            BarangDatang::updateFieldGudang('Total', 0, $invoice->gudangId);
            adminPurchaseInvoice::where('id', $request['invoiceId'])->delete();
        }

        return redirect('adminPO/Invoice');
    }
    //END PURCHASE INVOICE FUNCTION

    public function laporanAdminPO()
    {
        return view('adminPO.laporanPurchase.laporanAdminPO');
    }
}
