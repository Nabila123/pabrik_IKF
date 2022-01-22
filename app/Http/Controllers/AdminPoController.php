<?php

namespace App\Http\Controllers;
use App\Models\AdminPurchase;
use App\Models\MaterialModel;
use App\Models\AdminPurchaseDetail;

use Illuminate\Http\Request;

class AdminPoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('adminPO.index');
    }

    public function poRequest()
    {
        return view('adminPO.purchaseRequest.poRequest');
    }

    public function poRequestDetail()
    {
        return view('adminPO.purchaseRequest.poRequestDetail');
    }

    public function poOrder()
    {   
        $poOrder = AdminPurchase::get();

        return view('adminPO.purchaseOrder.poOrder', ['poOrder'=>$poOrder]);
    }

    public function poOrderCreate()
    {
        $materials = MaterialModel::get();
        $purchaseKode = AdminPurchase::purchaseKode();
        $getpurchaseKode = AdminPurchase::where('kode', $purchaseKode)->get();
        while (empty($getpurchaseKode)) {
            $purchaseKode = AdminPurchase::purchaseKode();
        }

        return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode]);
    }

    public function poOrderStore(Request $request)
    {
        
        $purchaseKode = $request['purchaseKode'];
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = !empty($request['pengirimanDate'])?date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])):date('Y-m-d H:i:s', strtotime(NULL));
        $jatuhTempoDate = !empty($request['jatuhTempoDate'])?date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])):date('Y-m-d H:i:s', strtotime(NULL));
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
            for ($i=0; $i < $jumlahData; $i++) { 
                $total += $totalHarga[$i];
            }            

            $AddPurchase = new AdminPurchase;
            $AddPurchase->kode = $purchaseKode;
            $AddPurchase->jenisPurchase = "Purchase Order";
            $AddPurchase->tanggal = $pengajuanDate;
            $AddPurchase->waktu = $pengirimanDate;
            $AddPurchase->waktuPayment = $jatuhTempoDate;
            $AddPurchase->note = $notePesan;
            $AddPurchase->total = $total;

            $AddPurchase->userId = \Auth::user()->id;
            $AddPurchase->created_at = date('Y-m-d H:i:s');

            if ($AddPurchase->save()) {
                $purchaseId = $AddPurchase->id;

                for ($i = 0; $i < $jumlahData; $i++) {
                    $addDetailPurchase = new AdminPurchaseDetail;
                    $addDetailPurchase->purchaseId = $purchaseId;
                    $addDetailPurchase->materialId = $material[$i];
                    $addDetailPurchase->qty = $jumlah[$i];
                    $addDetailPurchase->unit = $satuan[$i];
                    $addDetailPurchase->unitPrice = $harga[$i];
                    $addDetailPurchase->amount = $totalHarga[$i];
                    $addDetailPurchase->remark = $note[$i];
                    $addDetailPurchase->created_at = date('Y-m-d H:i:s');

                    $addDetailPurchase->save();                    
                }
                return redirect('adminPO/Order');
            }

        } else {
            $materials = MaterialModel::get();
            $purchaseKode = AdminPurchase::purchaseKode();
            $getpurchaseKode = AdminPurchase::where('kode', $purchaseKode)->get();
            while (empty($getpurchaseKode)) {
                $purchaseKode = AdminPurchase::purchaseKode();
            }
            return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode, 'message'=>'Material Belum Diisi']);
        }
        
    }

    public function poOrderDetail($id)
    {
        $getPurchaseId = AdminPurchase::where('id', $id)->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        return view('adminPO.purchaseOrder.poOrderDetail', ['purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function getDetail($kode)
    {
        $getPurchase = AdminPurchase::where('kode', $kode)->first();
        $getPurchaseDetail = AdminPurchaseDetail::where('purchaseId', $getPurchase->id)->get();
        foreach ($getPurchaseDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }
        return json_encode($getPurchaseDetail);
    }

    public function poOrderUpdate($id)
    {
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('id', $id)->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        
        return view('adminPO.purchaseOrder.update', ['materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poOrderUpdateSave(Request $request)
    {

        $purchaseid = $request['purchaseId'];
        $purchaseKode = $request['purchaseKode'];
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = !empty($request['pengirimanDate'])?date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])):date('Y-m-d H:i:s', strtotime(NULL));
        $jatuhTempoDate = !empty($request['jatuhTempoDate'])?date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])):date('Y-m-d H:i:s', strtotime(NULL));
        $notePesan = $request['notePesan'];
        $total = (int)$request['total'];

        $material = $request['material'];
        $jumlah = $request['jumlah'];
        $satuan = $request['satuan'];
        $harga = $request['harga'];
        $totalHarga = $request['totalHarga'];
        $note = $request['note'];

        $jumlahData = $request['jumlah_data'];

        if ($jumlahData != 0) {
            for ($i=0; $i < $jumlahData; $i++) { 
                $total += $totalHarga[$i];
            }

            $PurchaseUpdated['kode'] = $purchaseKode;
            $PurchaseUpdated['jenisPurchase'] = "Purchase Order";
            $PurchaseUpdated['tanggal'] = $pengajuanDate;
            $PurchaseUpdated['waktu'] = $pengirimanDate;
            $PurchaseUpdated['waktuPayment'] = $jatuhTempoDate;
            $PurchaseUpdated['note'] = $notePesan;
            $PurchaseUpdated['total'] = $total;

            $PurchaseUpdated['userId'] = \Auth::user()->id;
            $PurchaseUpdated['updated_at'] = date('Y-m-d H:i:s');

            AdminPurchase::where('id', $purchaseid)->update($PurchaseUpdated);

            for ($i = 0; $i < $jumlahData; $i++) {
                $addDetailPurchase = new AdminPurchaseDetail;
                $addDetailPurchase->purchaseId = $purchaseid;
                $addDetailPurchase->materialId = $material[$i];
                $addDetailPurchase->qty = $jumlah[$i];
                $addDetailPurchase->unit = $satuan[$i];
                $addDetailPurchase->unitPrice = $harga[$i];
                $addDetailPurchase->amount = $totalHarga[$i];
                $addDetailPurchase->remark = $note[$i];
                $addDetailPurchase->created_at = date('Y-m-d H:i:s');

                $addDetailPurchase->save();                    
            }
            return redirect('adminPO/Order');
        }else{
            $AddPurchase = new AdminPurchase;
            $AddPurchase->kode = $purchaseKode;
            $AddPurchase->jenisPurchase = "Purchase Order";
            $AddPurchase->tanggal = $pengajuanDate;
            $AddPurchase->waktu = $pengirimanDate;
            $AddPurchase->waktuPayment = $jatuhTempoDate;
            $AddPurchase->note = $notePesan;
            $AddPurchase->total = $total;

            $AddPurchase->userId = \Auth::user()->id;
            $AddPurchase->updated_at = date('Y-m-d H:i:s');
            $AddPurchase->save();      
            
            return redirect('adminPO/Order');
        }
    }

    public function poOrderDetailDelete($detailId, $purchaseId)
    {
        $purchaseDetail = AdminPurchaseDetail::where('id', $detailId)->delete();
        if ($purchaseDetail) {
            return redirect('adminPO/Order/update/' . $purchaseId . '');
        }
    }

    public function poOrderDelete(Request $request)
    {
        $PurchaseDetail = AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();

        if ($PurchaseDetail) {
            AdminPurchase::where('id', $request['purchaseId'])->delete();
        }
                
        return redirect('adminPO/Order');
    }

    public function laporanAdminPO()
    {
        return view('adminPO.laporanPurchase.laporanAdminPO');
    }
}
