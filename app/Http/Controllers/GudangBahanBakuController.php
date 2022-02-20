<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\GudangRajutMasuk;
use App\Models\GudangRajutKeluar;
use App\Models\GudangCuciKeluar;
use App\Models\GudangCompactMasuk;
use App\Models\GudangCompactKeluar;
use App\Models\GudangInspeksiKeluar;
use App\Models\GudangInspeksiMasuk;
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
        $data = GudangBahanBakuDetail::all();
        $materials = MaterialModel::all();
        $dataStok=[];
        foreach ($materials as $key => $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
        }

        foreach ($data as $key => $value) {
            $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $value->qtySaatIni;
        }
        
        $dataMasuk = GudangRajutMasuk::count() + GudangCompactMasuk::count() + GudangInspeksiMasuk::count();
        $dataKeluar = GudangRajutKeluar::count() + GudangCuciKeluar::count() +GudangCompactKeluar::count() + GudangInspeksiKeluar::count();
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

        $barangDatang = new BarangDatang;
        $barangDatang->purchaseId = $request['purchaseId'];
        $barangDatang->namaSuplier = $request['suplier'];

        //cek purchaseId sudah ada di gudang bahan baku atau belum
        $find = GudangBahanBaku::where('purchaseId',$request['purchaseId'])->first();
        if($find == null){
            $bahanBaku = new GudangBahanBaku;
            $bahanBaku->purchaseId = $request['purchaseId'];
            $bahanBaku->namaSuplier = $request['suplier'];
            $bahanBaku->total = 0;
            $bahanBaku->userId = \Auth::user()->id;
            $bahanBaku->save();
        }
            
        if($barangDatang->save()){
            for ($i=0; $i < $jumlahData; $i++) {
                $materialId = $request['materialId'][$i];
                $gramasi = $request['gramasi_'.$materialId];
                $diameter = $request['diameter_'.$materialId];
                $brutto = $request['brutto_'.$materialId];
                $netto = $request['netto_'.$materialId];
                $tarra = $request['tarra_'.$materialId];

                $barangDatangDetail = new BarangDatangDetail;
                $barangDatangDetail->barangDatangId = $barangDatang->id;
                $barangDatangDetail->materialId = $materialId;
                $barangDatangDetail->jumlah_datang = $request['qtySaatIni'][$i];
                $barangDatangDetail->save();

                if($find != null){

                    $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)
                                                            ->where('materialId',$materialId)
                                                            ->where('purchaseId',$request['purchaseId'])->first();

                    if($bahanBakuDetail){
                        $data['qtySaatIni'] = $bahanBakuDetail->qtySaatIni + $request['qtySaatIni'][$i];
                        $updateBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)
                                                            ->where('materialId',$materialId)
                                                            ->where('purchaseId',$request['purchaseId'])->update($data);

                    }else{
                        $bahanBakuDetail = new GudangBahanBakuDetail;
                        $bahanBakuDetail->gudangId = $find->id;
                        $bahanBakuDetail->purchaseId = $request['purchaseId'];
                        $bahanBakuDetail->materialId = $request['materialId'][$i];
                        $bahanBakuDetail->qtyPermintaan = $request['qtyPermintaan'][$i];
                        $bahanBakuDetail->qtySaatIni = $request['qtySaatIni'][$i];
                        $bahanBakuDetail->userId = \Auth::user()->id;
                        if($bahanBakuDetail->save()){
                        $saveStatus = 1;
                        } else {
                            $saveStatus = 0;
                            die();
                        }
                    }
                }else{
                    $bahanBakuDetail = new GudangBahanBakuDetail;
                    $bahanBakuDetail->gudangId = $bahanBaku->id;
                    $bahanBakuDetail->purchaseId = $request['purchaseId'];
                    $bahanBakuDetail->materialId = $request['materialId'][$i];
                    $bahanBakuDetail->qtyPermintaan = $request['qtyPermintaan'][$i];
                    $bahanBakuDetail->qtySaatIni = $request['qtySaatIni'][$i];
                    $bahanBakuDetail->userId = \Auth::user()->id;
                    if($bahanBakuDetail->save()){
                        $saveStatus = 1;
                    } else {
                        $saveStatus = 0;
                        die();
                    }
                }

                if($materialId == 1){
                    $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanBakuDetail->id)->first();

                    if($bahanBakuDetailMaterial){
                        $data['gramasi'] = $bahanBakuDetailMaterial->gramasi + $gramasi[0];
                        $data['diameter'] = $bahanBakuDetailMaterial->diameter + $diameter[0];
                        $data['brutto'] = $bahanBakuDetailMaterial->brutto + $brutto[0];
                        $data['netto'] = $bahanBakuDetailMaterial->netto + $netto[0];
                        $data['tarra'] = $bahanBakuDetailMaterial->tarra + $tarra[0];

                        $updateBahanBakuDetailMaterial =  GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanBakuDetail->id)->update($data);
                    }else{
                        $bahanBakuDetailMaterial = new GudangBahanBakuDetailMaterial;
                        $bahanBakuDetailMaterial->gudangDetailId = $bahanBakuDetail->id;
                        $bahanBakuDetailMaterial->diameter = $diameter[0];
                        $bahanBakuDetailMaterial->gramasi = $gramasi[0];
                        $bahanBakuDetailMaterial->brutto = $brutto[0];
                        $bahanBakuDetailMaterial->netto = $netto[0];
                        $bahanBakuDetailMaterial->tarra = $tarra[0];
                        $bahanBakuDetailMaterial->unit = $request['unit'][$i];
                        $bahanBakuDetailMaterial->unitPrice = $request['unitPrice'][$i];
                        $bahanBakuDetailMaterial->amount = $request['amount'][$i];
                        $bahanBakuDetailMaterial->remark = $request['remark'][$i];
                        $bahanBakuDetailMaterial->userId = \Auth::user()->id;

                        $bahanBakuDetailMaterial->save();
                    }
                }elseif($materialId == 2 || $materialId == 3){
                    $jumlah_roll = $request['jumlah_roll_'.$materialId];

                    for ($j=0; $j < $jumlah_roll ; $j++) { 
                        $bahanBakuDetailMaterial = new GudangBahanBakuDetailMaterial;
                        $bahanBakuDetailMaterial->gudangDetailId = $bahanBakuDetail->id;
                        $bahanBakuDetailMaterial->diameter = $diameter[$j];
                        $bahanBakuDetailMaterial->gramasi = $gramasi[$j];
                        $bahanBakuDetailMaterial->brutto = $brutto[$j];
                        $bahanBakuDetailMaterial->netto = $netto[$j];
                        $bahanBakuDetailMaterial->tarra = $tarra[$j];
                        $bahanBakuDetailMaterial->unit = $request['unit'][$i];
                        $bahanBakuDetailMaterial->unitPrice = $request['unitPrice'][$i];
                        $bahanBakuDetailMaterial->amount = $request['amount'][$i];
                        $bahanBakuDetailMaterial->remark = $request['remark'][$i];
                        $bahanBakuDetailMaterial->userId = \Auth::user()->id;

                        $bahanBakuDetailMaterial->save();
                    }
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
            $dataDetail['qtySaatIni'] = $request['qtySaatIni'][$i];
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
