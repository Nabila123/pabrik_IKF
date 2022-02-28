<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\GudangRajutMasuk;
use App\Models\GudangRajutMasukDetail;
use App\Models\GudangRajutKeluar;
use App\Models\GudangRajutKeluarDetail;
use App\Models\GudangCuciKeluar;
use App\Models\GudangCuciKeluarDetail;
use App\Models\GudangCompactMasuk;
use App\Models\GudangCompactMasukDetail;
use App\Models\GudangCompactKeluar;
use App\Models\GudangCompactKeluarDetail;
use App\Models\GudangInspeksiKeluar;
use App\Models\GudangInspeksiKeluarDetail;
use App\Models\GudangInspeksiMasuk;
use App\Models\GudangInspeksiMasukDetail;
use App\Models\GudangInspeksiStokOpname;
use App\Models\MaterialModel;
use App\Models\BarangDatang;
use App\Models\BarangDatangDetail;
use App\Models\PPICGudangRequest;

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

    public function ppicRequest()
    {
        $ppicRequest = PPICGudangRequest::all();
        return view('ppic.gudangRequest.index', ['ppicRequest' => $ppicRequest]);
    }

    public function terimaPPICRequest($id)
    {
        $id = $id;   
        $statusDiterima = 1;

        $gudangCuciTerima = PPICGudangRequest::updateStatusDiterima($id, $statusDiterima);

        if ($gudangCuciTerima == 1) {
            return redirect('bahan_baku/ppicRequest');
        }
    }

    public function keluarGudang()
    {
        $data = [];
        $data[0] = GudangRajutKeluar::all();
        $data[1] = GudangCuciKeluar::all();
        $data[2] = GudangCompactKeluar::all();
        $data[3] = GudangInspeksiKeluar::all();

        for ($i=0; $i < count($data); $i++) { 
            foreach ($data[$i] as $val) {
                switch ($i) {
                    case 0:
                        $val->gudangRequest = "Gudang Rajut";
                        break;
                    
                    case 1:
                        $val->gudangRequest = "Gudang Cuci";
                        $dataCompact = GudangCompactKeluar::where('gdCuciKId',$val->id)->first();
                        if ($dataCompact != null) {
                            $val->cuciDelete = false;
                        }
                        break;

                    case 2:
                        $val->gudangRequest = "Gudang Compact";
                        break;

                    case 3:
                        $val->gudangRequest = "Gudang Inspeksi";
                        break;
                }
            }
        }

        // dd($data);

        return view('bahanBaku.keluar.index')->with(['data'=>$data]);
    }

    public function createKeluarGudang()
    {
        // $datas = GudangStokOpname::all();
        $dataMaterial=[];
        $gudang = GudangBahanBaku::all();
        foreach ($gudang as $gd) {
            $gudangDetail = GudangBahanBakuDetail::where('gudangId', $gd->id)->get();
            foreach ($gudangDetail as $value) {
                $dataMaterial[]=$value->material;
            }
        }

        return view('bahanBaku.keluar.create')->with(['dataMaterial'=>$dataMaterial]);
    }
    

    public function getDataMaterial($gudangRequest)
    {
        $data['material'] = MaterialModel::where('jenisId',$gudangRequest)->first();
        $datas = GudangBahanBakuDetail::where('materialId',$data['material']->id)->get();

        $data['purchase'] = [];
        foreach($datas as $val){
            $data['purchase'][] = $val->purchase;
        }
        return json_encode($data);
    }


    public function getDataGudang($materialId,$purchaseId)
    {
        $datas['diameter'] = [];
        $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
        $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->get();
        
        $datas['gudangId'] = $detailGudang->gudangId;
        foreach ($gudangMaterialDetail as $detail) {
            if (!in_array($detail->diameter, $datas['diameter'])) {
                $datas['diameter'][] = $detail->diameter;
            }
        }

        return json_encode($datas);
    }

    public function getDataDetailMaterial($materialId, $purchaseId, $diameter, $gramasi="", $berat="")
    {
        $datas = [];
        if ($gramasi == "null") {
            $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->get();
        
            foreach ($gudangMaterialDetail as $detail) {
                if (!in_array($detail->gramasi, $datas)) {
                    $datas[] = $detail->gramasi;
                }
            }
        }elseif ($berat == "null") {
            $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->where('gramasi', $gramasi)->get();
        
            foreach ($gudangMaterialDetail as $detail) {
                if (!in_array($detail->netto, $datas)) {
                    $datas[] = $detail->netto;
                }
            }
        }else{
            $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->where('gramasi', $gramasi)->where('netto', $berat)->first();
            
            $datas['gudangMaterialDetail'] = $gudangMaterialDetail->id;
            $datas['qty'] = 1;
        }       

        return json_encode($datas);
    }

    public function storeKeluarGudang(Request $request)
    {
        // dd($request);
        $jumlahData = $request['jumlah_data'];        
        switch ($request['jenisId']) {
            case 1:
                $gudang = 'Gudang Rajut';
                $keluar = new GudangRajutKeluar;
                break;
            case 2:
                $gudang = 'Gudang Cuci';
                $keluar = new GudangCuciKeluar;
                break;
            case 3:
                $gudang = 'Gudang Inspeksi';
                $keluar = new GudangInspeksiKeluar;
                break;
        }

        $keluar->tanggal = date('Y-m-d');
        $keluar->userId = \Auth::user()->id;
        $keluar->created_at = date('Y-m-d H:i:s');
        if($keluar->save()){
            $gdId = $keluar->id;
            for ($i=0; $i < $jumlahData; $i++) { 
                if($request['jenisId'] == 1){
                    $keluarDetail = GudangRajutKeluarDetail::createGudangRajutKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 2){
                    $keluarDetail = GudangCuciKeluarDetail::createGudangCuciKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 3){
                    $keluarDetail = GudangInspeksiKeluarDetail::createGudangInspeksiKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }
            }
        }

        return redirect('/bahan_baku/keluar');
    }

    public function detailKeluarGudang($id, $gudangRequest)
    {
        switch ($gudangRequest) {
            case 'Gudang Rajut':
                $data = GudangRajutKeluar::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangRajutKeluarDetail::where('gdRajutKId',$id)->get();
                break;

            case 'Gudang Cuci':
                $data = GudangCuciKeluar::find($id);
                $data->gudangRequest = $gudangRequest;                
                $dataDetail = GudangCuciKeluarDetail::where('gdCuciKId',$id)->get();
                break;

            case 'Gudang Compact':
                $data = GudangCompactKeluar::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangCompactKeluarDetail::where('gdCompactKId',$id)->get();
                break;

            case 'Gudang Inspeksi':
                $data = GudangInspeksiKeluar::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId',$id)->get();
                break;
        }
        
        return view('bahanBaku.keluar.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function updateKeluarGudang($id, $gudangRequest)
    {
        switch ($gudangRequest) {
            case 'Gudang Rajut':
                $data = GudangRajutKeluar::find($id);
                $data->gudangRequestId = 1;
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangRajutKeluarDetail::where('gdRajutKId',$id)->get();
                break;

            case 'Gudang Cuci':
                $data = GudangCuciKeluar::find($id);
                $data->gudangRequestId = 2;                
                $data->gudangRequest = $gudangRequest;                
                $dataDetail = GudangCuciKeluarDetail::where('gdCuciKId',$id)->get();
                break;

            case 'Gudang Compact':
                $data = GudangCompactKeluar::find($id);
                $data->gudangRequestId = 3;
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangCompactKeluarDetail::where('gdCompactKId',$id)->get();
                break;

            case 'Gudang Inspeksi':
                $data = GudangInspeksiKeluar::find($id);
                $data->gudangRequestId = 4;
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId',$id)->get();
                break;
        }
        return view('bahanBaku.keluar.update', ['data' => $data, 'dataDetail' => $dataDetail, 'gudangRequest' => $gudangRequest]);
    }

    public function updateSaveKeluarGudang(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                if($request['jenisId'] == 1){
                    $keluarDetail = GudangRajutKeluarDetail::createGudangRajutKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 2){
                    $keluarDetail = GudangCuciKeluarDetail::createGudangCuciKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 3){
                    $keluarDetail = GudangInspeksiKeluarDetail::createGudangInspeksiKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }
            }

            if ($keluarDetail == 1) {
                return redirect('/bahan_baku/keluar');
            }
        }else {
            return redirect('/bahan_baku/keluar');
        }
    }

    public function deleteDetailGudang($gudangId, $detailId, $gudangRequest)
    {
        switch ($gudangRequest) {
            case 'Gudang Rajut':
                $dataDetail = GudangRajutKeluarDetail::where('id',$detailId)->delete();
                break;

            case 'Gudang Cuci':                
                $dataDetail = GudangCuciKeluarDetail::where('id',$detailId)->delete();
                break;

            case 'Gudang Compact':
                $dataDetail = GudangCompactKeluarDetail::where('id',$detailId)->delete();
                break;

            case 'Gudang Inspeksi':
                $dataDetail = GudangInspeksiKeluarDetail::where('id',$detailId)->delete();
                break;
        }
        if ($dataDetail) {
            return redirect('bahan_baku/keluar/update/' . $gudangId . '/' . $gudangRequest . '');
        }
    }

    public function deleteKeluarGudang(Request $request)
    {
        // dd($request);

        switch ($request->gudangRequestName) {
            case 'Gudang Rajut':
                $dataDetail = GudangRajutKeluarDetail::where('gdRajutKId',$request->gudangRequestId)->get();
                $dataDetailDelete = GudangRajutKeluarDetail::where('gdRajutKId',$request->gudangRequestId)->delete();
                if ($dataDetailDelete) {
                    GudangRajutKeluar::where('id', $request->gudangRequestId)->delete();
                }
                break;

            case 'Gudang Cuci':
                $dataDetail = GudangCuciKeluarDetail::where('gdCuciKId',$request->gudangRequestId)->get();
                $dataDetailDelete = GudangCuciKeluarDetail::where('gdCuciKId',$request->gudangRequestId)->delete();
                if ($dataDetailDelete) {
                    GudangCuciKeluar::where('id', $request->gudangRequestId)->delete();
                }
                break;

            case 'Gudang Compact':
                $dataDetail = GudangCompactKeluarDetail::where('gdCompactKId',$request->gudangRequestId)->get();
                $dataDetailDelete = GudangCompactKeluarDetail::where('gdCompactKId',$request->gudangRequestId)->delete();
                if ($dataDetailDelete) {
                    GudangCompactKeluar::where('id', $request->gudangRequestId)->delete();
                }
                break;

            case 'Gudang Inspeksi':
                $dataDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId',$request->gudangRequestId)->get();
                $dataDetailDelete = GudangInspeksiKeluarDetail::where('gdInspeksiKId',$request->gudangRequestId)->delete();
                if ($dataDetailDelete) {
                    GudangInspeksiKeluar::where('id', $request->gudangRequestId)->delete();
                }
                break;
        }
        
        // if ($dataDetailDelete) {
        //     GudangKeluar::where('id', $request['gudangId'])->delete();
        //     foreach ($dataDetail as $value) {
        //         $getStok = GudangStokOpname::find($value->gudangStokId);
        //         $qty = $getStok->qty + $value->qty;
        //         $update = GudangStokOpname::where('id',$value->gudangStokId)->update(['qty'=>$qty]);
        //     }
        // }
                
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
