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
        return view('adminPO.purchaseOrder.create', ['materials' => $materials]);
    }

    public function poOrderStore(Request $request)
    {
        $purchaseKode = $request['purchaseKode'];
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = date('Y-m-d H:i:s', strtotime($request['pengirimanDate']));
        $jatuhTempoDate = date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate']));
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
            return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'message'=>'Material Belum Diisi']);
        }
        
    }

    public function poOrderDetail()
    {
        return view('adminPO.purchaseOrder.poOrderDetail');
    }

    public function laporanAdminPO()
    {
        return view('adminPO.laporanPurchase.laporanAdminPO');
    }
}
