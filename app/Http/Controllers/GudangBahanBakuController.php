<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangKeluar;
use App\Models\GudangKeluarDetail;
use App\Models\GudangStokOpname;
use App\Models\GudangInspeksiStokOpname;
use App\Models\MaterialModel;
use App\Models\BarangDatang;
use App\Models\BarangDatangDetail;

class GudangBahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = GudangStokOpname::all();
        $materials = MaterialModel::all();
        $dataStok=[];
        foreach ($materials as $key => $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
        }
        foreach ($data as $key => $value) {
            $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $value->qty;
        }
    
        $dataMasuk = GudangMasuk::count();
        $dataKeluar = GudangKeluar::count();
        return view('bahanBaku.index')->with(['dataStok'=>$dataStok,'dataMasuk'=>$dataMasuk,'dataKeluar'=>$dataKeluar]);
    }

    public function indexSupplyBarang(){
        $data = GudangBahanBaku::all();
        return view('bahanBaku.supply.index')->with(['data'=>$data]);
    }

    public function create()
    {
        $purchases = AdminPurchase::where('jenisPurchase', 'Purchase Order')->get();
        return view('bahanBaku.supply.create')->with(['purchases'=>$purchases]);
    }

    public function store(Request $request)
    {
        $jumlahData = $request['jumlah_data'];

        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('kode',$request['kodePurchase'])->first();

        $barangDatang = new BarangDatang;
        $barangDatang->purchaseId = $purchase->id;
        $barangDatang->namaSuplier = $request['suplier'];
        $barangDatang->userId = \Auth::user()->id;

        //cek kodePurchase sudah ada di gudang bahan baku atau belum
        $find = GudangBahanBaku::where('kodePurchase',$request['kodePurchase'])->first();
        if($find == null){
            $bahanBaku = new GudangBahanBaku;
            $bahanBaku->kodePurchase = $request['kodePurchase'];
            $bahanBaku->namaSuplier = $request['suplier'];
            $bahanBaku->total = $request['total'];
            $bahanBaku->userId = \Auth::user()->id;
            $bahanBaku->save();
        }
            
        if($barangDatang->save()){
            for ($i=0; $i < $jumlahData; $i++) { 
                $barangDatangDetail = new BarangDatangDetail;
                $barangDatangDetail->barangDatangId = $barangDatang->id;
                $barangDatangDetail->materialId = $request['materialId'][$i];
                $barangDatangDetail->jumlah_datang = $request['qty_saat_ini'][$i];
                $barangDatangDetail->save();

                if($find != null){

                    $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)->where('materialId',$request['materialId'][$i])->first();

                    $stokOpname = GudangStokOpname::where('purchaseId',$purchase->id)->where('materialId',$request['materialId'][$i])->first();

                    if($bahanBakuDetail && $stokOpname){
                        $data['qty_saat_ini'] = $bahanBakuDetail->qty_saat_ini + $request['qty_saat_ini'][$i];
                        $data['gramasi'] = $bahanBakuDetail->gramasi + $request['gramasi'][$i];
                        $data['diameter'] = $bahanBakuDetail->diameter + $request['diameter'][$i];
                        $data['brutto'] = $bahanBakuDetail->brutto + $request['brutto'][$i];
                        $data['netto'] = $bahanBakuDetail->netto + $request['netto'][$i];
                        $data['tarra'] = $bahanBakuDetail->tarra + $request['tarra'][$i];

                        $updateBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)->where('materialId',$request['materialId'][$i])->update($data);

                        $dataStokOpname['qty'] = $stokOpname->qty + $request['netto'][$i];

                        $updateStokOpname = GudangStokOpname::where('purchaseId',$purchase->id)->where('materialId',$request['materialId'][$i])->update($dataStokOpname);
                    }else{
                        $bahanBakuDetail = new GudangBahanBakuDetail;
                        $bahanBakuDetail->gudangId = $bahanBaku->id;
                        $bahanBakuDetail->materialId = $request['materialId'][$i];
                        $bahanBakuDetail->qty_permintaan = $request['qty_permintaan'][$i];
                        $bahanBakuDetail->qty_saat_ini = $request['qty_saat_ini'][$i];
                        $bahanBakuDetail->diameter = $request['diameter'][$i];
                        $bahanBakuDetail->gramasi = $request['gramasi'][$i];
                        $bahanBakuDetail->brutto = $request['brutto'][$i];
                        $bahanBakuDetail->netto = $request['netto'][$i];
                        $bahanBakuDetail->tarra = $request['tarra'][$i];
                        $bahanBakuDetail->unit = $request['unit'][$i];
                        $bahanBakuDetail->unitPrice = $request['unitPrice'][$i];
                        $bahanBakuDetail->amount = $request['amount'][$i];
                        $bahanBakuDetail->remark = $request['remark'][$i];
                        if($bahanBakuDetail->save()){
                        $saveStatus = 1;
                        } else {
                            $saveStatus = 0;
                            die();
                        }

                        //get jenisId
                        $material = MaterialModel::find($request['materialId'][$i]);

                        //input ke gudang stok opname
                        $stokOpname = new GudangStokOpname;
                        $stokOpname->purchaseId = $purchase->id;
                        $stokOpname->materialId = $request['materialId'][$i];
                        $stokOpname->jenisId = $material->jenisId;
                        $stokOpname->qty = $request['netto'][$i];
                        $stokOpname->userId = \Auth::user()->id;

                        $stokOpname->save();
                    }
                }else{
                    $bahanBakuDetail = new GudangBahanBakuDetail;
                    $bahanBakuDetail->gudangId = $bahanBaku->id;
                    $bahanBakuDetail->materialId = $request['materialId'][$i];
                    $bahanBakuDetail->qty_permintaan = $request['qty_permintaan'][$i];
                    $bahanBakuDetail->qty_saat_ini = $request['qty_saat_ini'][$i];
                    $bahanBakuDetail->diameter = $request['diameter'][$i];
                    $bahanBakuDetail->gramasi = $request['gramasi'][$i];
                    $bahanBakuDetail->brutto = $request['brutto'][$i];
                    $bahanBakuDetail->netto = $request['netto'][$i];
                    $bahanBakuDetail->tarra = $request['tarra'][$i];
                    $bahanBakuDetail->unit = $request['unit'][$i];
                    $bahanBakuDetail->unitPrice = $request['unitPrice'][$i];
                    $bahanBakuDetail->amount = $request['amount'][$i];
                    $bahanBakuDetail->remark = $request['remark'][$i];
                    if($bahanBakuDetail->save()){
                        $saveStatus = 1;
                    } else {
                        $saveStatus = 0;
                        die();
                    }

                    //get jenisId
                    $material = MaterialModel::find($request['materialId'][$i]);

                    //input ke gudang stok opname
                    $stokOpname = new GudangStokOpname;
                    $stokOpname->purchaseId = $purchase->id;
                    $stokOpname->materialId = $request['materialId'][$i];
                    $stokOpname->jenisId = $material->jenisId;
                    $stokOpname->qty = $request['netto'][$i];
                    $stokOpname->userId = \Auth::user()->id;

                    $stokOpname->save();
                }
                    

                    
            }
        }

        return redirect('/bahan_baku/supply');
    }

    public function detail($id)
    {
        $data = GudangBahanBaku::find($id);
        $dataDetail = GudangBahanBakuDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.supply.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function edit($id)
    {
        $data = GudangBahanBaku::find($id);
        $dataDetail = GudangBahanBakuDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.supply.update')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function update($id, Request $request)
    {
        $data['kodePurchase'] = $request['kodePurchase'];
        $data['namaSuplier'] = $request['namaSuplier'];
        $data['total'] = $request['total'];
        $data['userId'] = \Auth::user()->id;

        $updateBahanBaku = GudangBahanBaku::where('id',$id)->update($data);

        //get purchaseId
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('kode',$request['kodePurchase'])->first();

        for ($i=0; $i < $request['jumlah_data']; $i++) { 
            $dataDetail['gudangId'] = $id;
            $dataDetail['materialId'] = $request['materialId'][$i];
            $dataDetail['qty_saat_ini'] = $request['qty_saat_ini'][$i];
            $dataDetail['brutto'] = $request['brutto'][$i];
            $dataDetail['diameter'] = $request['diameter'][$i];
            $dataDetail['gramasi'] = $request['gramasi'][$i];
            $dataDetail['netto'] = $request['netto'][$i];
            $dataDetail['tarra'] = $request['tarra'][$i];
            $dataDetail['unit'] = $request['unit'][$i];
            $dataDetail['unitPrice'] = $request['unitPrice'][$i];
            $dataDetail['amount'] = $request['amount'][$i];
            $dataDetail['remark'] = $request['remark'][$i];

            $updateBahanBakuDetail = GudangBahanBakuDetail::where('id',$request['detailId'][$i])->update($dataDetail);

            //get jenisId
            $material = MaterialModel::find($request['materialId'][$i]);

            $dataStokOpname['qty'] = $request['netto'][$i];
            $dataStokOpname['userId'] = \Auth::user()->id;

            $updateStokOpname = GudangStokOpname::where('purchaseId', $purchase->id)->where('materialId',$request['materialId'][$i])->update($dataStokOpname);
        }

        return redirect('bahan_baku/supply');

    }

    public function delete(Request $request)
    {
        $kodePurchase = GudangBahanBaku::find($request['gudangId']);
        $purchase = AdminPurchase::where('jenisPurchase', 'Purchase Order')->where('kode',$kodePurchase->kodePurchase)->first();
        $delStokOpname = GudangStokOpname::where('purchaseId',$purchase->id)->delete();

        $gudangDetail = GudangBahanBakuDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangBahanBaku::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('bahan_baku/supply');
    }

    public function keluarGudang()
    {
        $data = GudangKeluar::all();
        return view('bahanBaku.keluar.index')->with(['data'=>$data]);
    }

    public function createKeluarGudang()
    {
        $datas = GudangStokOpname::all();
        $dataMaterial=[];
        foreach ($datas as $key => $value) {
            $dataMaterial[]=$value->material;
        }
        return view('bahanBaku.keluar.create')->with(['dataMaterial'=>$dataMaterial]);
    }

    public function getDataMaterial($gudangRequest)
    {
        $data['material'] = MaterialModel::where('jenisId',$gudangRequest)->first();
        $datas = GudangStokOpname::where('materialId',$data['material']->id)->get();
        $data['purchase'] = [];
        foreach($datas as $val){
            $data['purchase'][] = $val->purchase;
        }
        return json_encode($data);
    }


    public function getDataGudang($materialId,$purchaseId)
    {
        $datas = GudangStokOpname::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
    
        return json_encode($datas);
    }

    public function storeKeluarGudang(Request $request)
    {
        $jumlahData = $request['jumlah_data'];
        $keluar = new GudangKeluar;
        $keluar->materialId = $request['materialId'];
        $keluar->jenisId = $request['jenisId'];
        switch ($request['jenisId']) {
            case 1:
                $gudang = 'Gudang Rajut';
                break;
            case 2:
                $gudang = 'Gudang Cuci';
                break;
            case 3:
                $gudang = 'Gudang Inspeksi';
                break;
            
            default:
                // code...
                break;
        }
        $keluar->gudangRequest = $gudang;
        $keluar->tanggal = date('Y-m-d');
        $keluar->userId = \Auth::user()->id;

        if($keluar->save()){
            for ($i=0; $i < $jumlahData; $i++) { 
                $keluarDetail = new GudangKeluarDetail;
                $keluarDetail->gudangKeluarId = $keluar->id;
                $keluarDetail->gudangStokId = $request['gStokIdArr'][$i];
                $keluarDetail->purchaseId = $request['purchaseIdArr'][$i];
                $keluarDetail->qty = $request['qtyArr'][$i];
                if($keluarDetail->save()){
                    $stokOpname = GudangStokOpname::where('materialId', $request['materialId'])->where('purchaseId',$request['purchaseIdArr'][$i])->first();
                    $qtyUpdate = $stokOpname->qty - $request['qtyArr'][$i];

                    $update = GudangStokOpname::where('materialId', $request['materialId'])->where('purchaseId',$request['purchaseIdArr'][$i])->update(['qty'=>$qtyUpdate]);
                }
            }
        }

        return redirect('/bahan_baku/keluar');
    }

    public function detailKeluarGudang($id)
    {
        $data = GudangKeluar::find($id);
        $dataDetail = GudangKeluarDetail::where('gudangKeluarId',$id)->get();

        return view('bahanBaku.keluar.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function deleteKeluarGudang(Request $request)
    {
        $getDetail = GudangKeluarDetail::where('gudangKeluarId', $request['gudangId'])->get();
        $gudangDetail = GudangKeluarDetail::where('gudangKeluarId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangKeluar::where('id', $request['gudangId'])->delete();
            foreach ($getDetail as $key => $value) {
                $getStok = GudangStokOpname::find($value->gudangStokId);
                $qty = $getStok->qty + $value->qty;
                $update = GudangStokOpname::where('id',$value->gudangStokId)->update(['qty'=>$qty]);
            }
        }
                
        return redirect('bahan_baku/keluar');
    }

    public function masukGudang()
    {
        $data = GudangMasuk::all();
        return view('bahanBaku.masuk.index')->with(['data'=>$data]);
    }


    public function detailMasukGudang($id)
    {
        $data = GudangMasuk::find($id);
        $dataDetail = GudangMasukDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.masuk.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function terimaMasukGudang($id){

        $findMasukGudang = GudangMasuk::find($id); 
        $gudangRequest = $findMasukGudang->gudangRequest;  
        $statusDiterima = 1;  

        $gudangTerima = GudangMasuk::updateStatusDiterima($id, $gudangRequest, $statusDiterima);
            
        if ($gudangTerima == 1) {
            $detailGudangMasuk = GudangMasukDetail::where('gudangMasukId',$id)->get();
            foreach ($detailGudangMasuk as $key => $value) {
                if($gudangRequest == 'Gudang Inspeksi'){
                    $getInspeksiStok = GudangInspeksiStokOpname::where('gudangStokId',$value->gudangStokId)->where('purchaseId',$value->purchaseId)->where('materialId',$findMasukGudang->materialId)->first();
                    $qty = $getInspeksiStok->qty + $value->qty;
                    $updateInspeksi = GudangInspeksiStokOpname::where('gudangStokId',$value->gudangStokId)->where('purchaseId',$value->purchaseId)->where('materialId',$findMasukGudang->materialId)->update(['qty'=>$qty]);
                }else{
                    $getStokOpname = GudangStokOpname::where('id',$value->gudangStokId)->first();
                    $qty = $getStokOpname->qty + $value->qty;

                    $update = GudangStokOpname::where('id',$value->gudangStokId)->update(['qty'=>$qty]); 
                }
            }
                
            return redirect('bahan_baku/masuk');
        }
    }

}
