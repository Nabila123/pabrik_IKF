<?php

namespace App\Http\Controllers;
use App\Models\AdminPurchase;
use App\Models\MaterialModel;
use App\Models\AdminPurchaseDetail;
use PDF;

use Illuminate\Http\Request;

class AdminPoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //PURCHASE ORDER FUNCTION
    public function index(){
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();
        $poRequest = AdminPurchase::where('jenisPurchase', 'Purchase Request')->get();

        $poOrder = count($poOrder);
        $poRequest = count($poRequest);

        return view('adminPO.index', ['order' => $poOrder, 'request' => $poRequest]);
    }    

    public function poOrder(){   
        $poOrder = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();

        return view('adminPO.purchaseOrder.index', ['poOrder'=>$poOrder]);
    }

    public function poOrderCreate(){
        $materials = MaterialModel::get();
        $purchaseKode = AdminPurchase::purchaseKode();
        $getpurchaseKode = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('kode', $purchaseKode)->get();
        while (empty($getpurchaseKode)) {
            $purchaseKode = AdminPurchase::purchaseKode();
        }

        return view('adminPO.purchaseOrder.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode]);
    }
    
    public function poOrderStore(Request $request){
        
        $purchaseKode = $request['purchaseKode'];
        $suplierName = $request['suplierName'];
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

            $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
            
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

    public function poOrderDetail($id){
        $getPurchaseId = AdminPurchase::where('id', $id)->where('jenisPurchase', 'Purchase Order')->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        return view('adminPO.purchaseOrder.detail', ['purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
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

     public function getSuplier($kode)
    {
        $getPurchase = AdminPurchase::where('kode', $kode)->first();
        return json_encode($getPurchase->suplierName);
    }

    public function poOrderUpdate($id){
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('id', $id)->where('jenisPurchase', 'Purchase Order')->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        
        return view('adminPO.purchaseOrder.update', ['materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poOrderUpdateSave(Request $request){
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

    public function poOrderUnduh($id)
    {
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('id', $id)->first();
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $id)->get();
        
    	$pdf = PDF::loadview('adminPO.purchaseOrder.unduh',['purchase' => $purchase, 'purchaseDetail' => $purchaseDetail])
                ->setPaper('a5', 'potrait');
    	return $pdf->stream();
    }

    public function poOrderDetailDelete($detailId, $purchaseId){
        $purchaseDetail = AdminPurchaseDetail::where('id', $detailId)->delete();
        if ($purchaseDetail) {
            return redirect('adminPO/Request/update/' . $purchaseId . '');
        }
    }

    public function poOrderDelete(Request $request){
        AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();        
        AdminPurchase::where('id', $request['purchaseId'])->delete();   
                
        return redirect('adminPO/Order');
    }
    //END PURCHASE ORDER FUNCTION

    //PURCHASE REQUEST FUNCTION
    public function poRequest(){
        $poRequest = AdminPurchase::where('jenisPurchase', 'Purchase Request')->get();
        foreach ($poRequest as $request) {
            $cek = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('kode', $request->kode)->first();
            if ($cek != null) {
                $request['prosesOrder'] = true;
                $request['prosesOrderAt'] = $cek->tanggal;
            }
        }

        return view('adminPO.purchaseRequest.index', ['poRequest' => $poRequest]);
    }

    public function poRequestCreate(){
        $materials = MaterialModel::get();
        $purchaseKode = AdminPurchase::purchaseKode();
        $getpurchaseKode = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('kode', $purchaseKode)->get();
        while (empty($getpurchaseKode)) {
            $purchaseKode = AdminPurchase::purchaseKode();
        }

        return view('adminPO.purchaseRequest.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode]);
    }
    
    public function poRequestStore(Request $request){        
        $purchaseKode = $request['purchaseKode'];
        $suplierName = isset($request['suplierName'])?$request['suplierName']:null;
        $jenisPurchase = "Purchase Request";
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = !empty($request['pengirimanDate'])?date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])):null;
        $jatuhTempoDate = !empty($request['jatuhTempoDate'])?date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])):null;
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

            $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
            
            if ($purchaseCreate) {
                $purchaseId = $purchaseCreate;

                for ($i = 0; $i < $jumlahData; $i++) {
                    AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $material[$i], $jumlah[$i], $satuan[$i], $harga[$i], $totalHarga[$i], $note[$i]);                       
                }
                return redirect('adminPO/Request');
            }

        } else {
            $materials = MaterialModel::get();
            $purchaseKode = AdminPurchase::purchaseKode();
            $getpurchaseKode = AdminPurchase::where('kode', $purchaseKode)->get();
            while (empty($getpurchaseKode)) {
                $purchaseKode = AdminPurchase::purchaseKode();
            }
            return view('adminPO.purchaseRequest.create', ['materials' => $materials, 'purchaseKode' => $purchaseKode, 'message'=>'Material Belum Diisi']);
        }       
        
    }

    public function poRequestUpdate($id){
        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('id', $id)->where('jenisPurchase', 'Purchase Request')->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();
        return view('adminPO.purchaseOrder.update', ['materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poRequestUpdateSave(Request $request){
        $purchaseid = $request['purchaseId'];
        $purchaseKode = $request['purchaseKode'];
        $jenisPurchase = "Purchase Request";
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
                return redirect('adminPO/Request');
            }
        }else{
            $purchaseUpdate = AdminPurchase::purchaseUpdate($purchaseid, $purchaseKode, $jenisPurchase, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);           
            return redirect('adminPO/Request');
        }
    }

    public function poRequestRequestKode($purchaseKode){

        $materials = MaterialModel::get();
        $getPurchaseId = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('kode', $purchaseKode)->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $getPurchaseId->id)->get();
        $jenisPurchase = "Request";


        return view('adminPO.purchaseOrder.create', ['jenisPurchase' => $jenisPurchase, 'purchaseKode' => $purchaseKode, 'materials' => $materials, 'purchase' => $getPurchaseId, 'purchaseDetails' => $getPurchaseDetailId]);
    }

    public function poOrderRequestStore(Request $request){
        $purchaseid = $request['id'];
        $purchaseKode = $request['purchaseKode'];
        $suplierName = $request['suplierName'];
        $jenisPurchase = "Purchase Order";
        $pengajuanDate = date('Y-m-d H:i:s', strtotime($request['pengajuanDate']));
        $pengirimanDate = !empty($request['pengirimanDate'])?date('Y-m-d H:i:s', strtotime($request['pengirimanDate'])):null;
        $jatuhTempoDate = !empty($request['jatuhTempoDate'])?date('Y-m-d H:i:s', strtotime($request['jatuhTempoDate'])):null;
        $notePesan = $request['notePesan'];
        $total = $request['total'];

        $purchaseCreate = AdminPurchase::purchaseCreate($purchaseKode, $jenisPurchase, $suplierName, $pengajuanDate, $pengirimanDate, $jatuhTempoDate, $notePesan, $total, \Auth::user()->id);
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $purchaseid)->get();
        
        $purchaseId = $purchaseCreate;
        foreach ($purchaseDetail as $detail) {
            AdminPurchaseDetail::purchaseDetailCreate($purchaseId, $detail->materialId, $detail->qty, $detail->unit, $detail->unitPrice, $detail->amount, $detail->remark);                       
        } 

        return redirect('adminPO/Order');
    }

    public function poRequestApprove(Request $request){
        $Approved = AdminPurchase::purchaseUpdateField($request['approve'], \Auth::user()->roleId, $request['purchaseId']);
        if ($Approved == 1) {
            $ApprovedAt = AdminPurchase::purchaseUpdateField($request['approveAt'], date('Y-m-d H:i:s'), $request['purchaseId']);

            if ($ApprovedAt == 1) {
                return 1;
            }
        }
    }

    public function poRequestDetail($id){
        $getPurchaseId = AdminPurchase::where('id', $id)->where('jenisPurchase', 'Purchase Request')->first();
        $getPurchaseDetailId = AdminPurchaseDetail::where('purchaseId', $id)->get();

        return view('adminPO.purchaseRequest.detail', ['request' => $getPurchaseId, 'requestDetail' => $getPurchaseDetailId]);
    }

    public function poRequestUnduh($id)
    {
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Request')->where('id', $id)->first();
        $purchaseDetail = AdminPurchaseDetail::where('purchaseId', $id)->get();
        
    	$pdf = PDF::loadview('adminPO.purchaseRequest.unduh',['purchase' => $purchase, 'purchaseDetail' => $purchaseDetail])
                ->setPaper('a5', 'potrait');
    	return $pdf->stream();
    }

    public function poRequestDelete(Request $request){
        AdminPurchaseDetail::where('purchaseId', $request['purchaseId'])->delete();        
        AdminPurchase::where('id', $request['purchaseId'])->delete();   
                
        return redirect('adminPO/Request');
    }
    //END PURCHASE REQUEST FUNCTION

    public function laporanAdminPO(){
        return view('adminPO.laporanPurchase.laporanAdminPO');
    }
}
