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
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();
        $poRequest = AdminPurchase::where('jenisPurchase', 'Purchase Request')->get();

        $poOrder = count($poOrder);
        $poRequest = count($poRequest);

        return view('adminPO.index', ['order' => $poOrder, 'request' => $poRequest]);
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
        $jenisPurchase = "Purchase Order";
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

            $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
            
            if ($purchaseCreate) {
                $purchaseId = $purchaseCreate;

                for ($i = 0; $i < $jumlahData; $i++) {
                    AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $material[$i], $jumlah[$i], $satuan[$i], $harga[$i], $totalHarga[$i], $note[$i]);                  
                    
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
        $jenisPurchase = "Purchase Order";
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

            $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);

            if ($purchaseUpdate == 1) {
                for ($i = 0; $i < $jumlahData; $i++) {
                    AdminPurchaseDetail::purchaseDetailCreate($purchaseid, $material[$i], $jumlah[$i], $satuan[$i], $harga[$i], $totalHarga[$i], $note[$i]);                  
                }
                return redirect('adminPO/Order');
            }
        }else{
            $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);           
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
        AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();        
        AdminPurchase::where('id', $request['purchaseId'])->delete();   
                
        return redirect('adminPO/Order');
    }

    public function laporanAdminPO()
    {
        return view('adminPO.laporanPurchase.laporanAdminPO');
    }
}
