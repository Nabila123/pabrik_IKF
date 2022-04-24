<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\AdminPurchaseDetail;
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
use App\Models\gudangInspeksiStokOpnameDetail;
use App\Models\GudangPotongKeluar;
use App\Models\GudangPotongKeluarDetail;
use App\Models\MaterialModel;
use App\Models\BarangDatang;
use App\Models\BarangDatangDetail;
use App\Models\PPICGudangRequest;
use App\Models\BarangDatangDetailMaterial;
use Illuminate\Database\Eloquent\Builder;

class GudangBahanPembantuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = GudangBahanBakuDetail::all();
        $materials = MaterialModel::where('keterangan','LIKE','%Bahan Bantu / Merchandise%')->get();
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
        return view('bahanPembantu.index')->with(['dataStok'=>$dataStok,'dataMasuk'=>$dataMasuk,'dataKeluar'=>$dataKeluar]);
    }

    public function indexSupplyBarang(){
        $data = BarangDatangDetail::groupBy('purchaseId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();

        return view('bahanPembantu.supply.index')->with(['data'=>$data]);
    }

    public function create()
    {
        $purchases = AdminPurchaseDetail::groupBy('purchaseId')
                            ->whereHas('material', function (Builder $query) {
                             $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                            })
                            ->whereHas('purchase', function (Builder $query) {
                             $query->where('jenisPurchase', 'Purchase Order');
                            })
                            ->get();
        return view('bahanPembantu.supply.create')->with(['purchases'=>$purchases]);
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
            $bahanPembantu = new GudangBahanBaku;
            $bahanPembantu->purchaseId = $request['purchaseId'];
            $bahanPembantu->namaSuplier = $request['suplier'];
            $bahanPembantu->total = 0;
            $bahanPembantu->userId = \Auth::user()->id;
            $bahanPembantu->save();
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
                $barangDatangDetail->purchaseId = $request['purchaseId'];
                $barangDatangDetail->materialId = $materialId;
                $barangDatangDetail->qtyPermintaan = $request['qtyPermintaan'][$i];
                $barangDatangDetail->jumlah_datang = $request['qtySaatIni'][$i];
                $barangDatangDetail->save();

                if($find != null){

                    $bahanPembantuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)
                                                            ->where('materialId',$materialId)
                                                            ->where('purchaseId',$request['purchaseId'])->first();

                    if($bahanPembantuDetail){
                        $data['qtySaatIni'] = $bahanPembantuDetail->qtySaatIni + $request['qtySaatIni'][$i];
                        $updateBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$find->id)
                                                            ->where('materialId',$materialId)
                                                            ->where('purchaseId',$request['purchaseId'])->update($data);

                    }else{
                        $bahanPembantuDetail = new GudangBahanBakuDetail;
                        $bahanPembantuDetail->gudangId = $find->id;
                        $bahanPembantuDetail->purchaseId = $request['purchaseId'];
                        $bahanPembantuDetail->materialId = $request['materialId'][$i];
                        $bahanPembantuDetail->qtyPermintaan = $request['qtyPermintaan'][$i];
                        $bahanPembantuDetail->qtySaatIni = $request['qtySaatIni'][$i];
                        $bahanPembantuDetail->userId = \Auth::user()->id;
                        if($bahanPembantuDetail->save()){
                        $saveStatus = 1;
                        } else {
                            $saveStatus = 0;
                            die();
                        }
                    }
                }else{
                    $bahanPembantuDetail = new GudangBahanBakuDetail;
                    $bahanPembantuDetail->gudangId = $bahanPembantu->id;
                    $bahanPembantuDetail->purchaseId = $request['purchaseId'];
                    $bahanPembantuDetail->materialId = $request['materialId'][$i];
                    $bahanPembantuDetail->qtyPermintaan = $request['qtyPermintaan'][$i];
                    $bahanPembantuDetail->qtySaatIni = $request['qtySaatIni'][$i];
                    $bahanPembantuDetail->userId = \Auth::user()->id;
                    if($bahanPembantuDetail->save()){
                        $saveStatus = 1;
                    } else {
                        $saveStatus = 0;
                        die();
                    }
                }


                    $bahanPembantuDetailMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanPembantuDetail->id)->first();

                    if($bahanPembantuDetailMaterial){
                        $dataDetail['gramasi'] = $bahanPembantuDetailMaterial->gramasi + $gramasi[0];
                        $dataDetail['diameter'] = $bahanPembantuDetailMaterial->diameter + $diameter[0];
                        $dataDetail['brutto'] = $bahanPembantuDetailMaterial->brutto + $brutto[0];
                        $dataDetail['netto'] = $bahanPembantuDetailMaterial->netto + $netto[0];
                        $dataDetail['tarra'] = $bahanPembantuDetailMaterial->tarra + $tarra[0];
                        $dataDetail['qty'] = $bahanPembantuDetailMaterial->qty + 1;

                        $updateBahanBakuDetailMaterial =  GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanPembantuDetail->id)->update($dataDetail);
                    }else{
                        $bahanPembantuDetailMaterial = new GudangBahanBakuDetailMaterial;
                        $bahanPembantuDetailMaterial->gudangDetailId = $bahanPembantuDetail->id;
                        $bahanPembantuDetailMaterial->diameter = $diameter[0];
                        $bahanPembantuDetailMaterial->gramasi = $gramasi[0];
                        $bahanPembantuDetailMaterial->brutto = $brutto[0];
                        $bahanPembantuDetailMaterial->netto = $netto[0];
                        $bahanPembantuDetailMaterial->tarra = $tarra[0];
                        $bahanPembantuDetailMaterial->qty = 1;
                        $bahanPembantuDetailMaterial->unit = $request['unit'][$i];
                        $bahanPembantuDetailMaterial->unitPrice = $request['unitPrice'][$i];
                        $bahanPembantuDetailMaterial->amount = $request['amount'][$i];
                        $bahanPembantuDetailMaterial->remark = $request['remark'][$i];
                        $bahanPembantuDetailMaterial->userId = \Auth::user()->id;

                        $bahanPembantuDetailMaterial->save();
                    }

                    BarangDatangDetailMaterial::createDetailMaterial($barangDatangDetail->id, $diameter[0], $gramasi[0], $brutto[0], $netto[0], $tarra[0], $request['unit'][$i], $request['unitPrice'][$i], $request['amount'][$i], $request['remark'][$i]);
            }
        }

        return redirect('/GBahanPembantu/supply');
    }

    public function detail($id)
    {
        $barangDatang = BarangDatang::where('purchaseId',$id)->get();
        $data = $barangDatang->first();
        foreach ($barangDatang as $key => $value) {
            $value->detail = BarangDatangDetail::where('barangDatangId', $value->id)->get();
            foreach ($value->detail as $key => $value2) {
                $value2->detailMaterial = BarangDatangDetailMaterial::where('barangDatangDetailId', $value2->id)->get();
            }
        }
        return view('bahanPembantu.supply.detail')->with(['barangDatang'=>$barangDatang, 'data'=>$data]);
    }

    public function edit($id)
    {
        $barangDatang = BarangDatang::where('purchaseId',$id)->get();
        $data = $barangDatang->first();
        foreach ($barangDatang as $key => $value) {
            $value->detail = BarangDatangDetail::where('barangDatangId', $value->id)->get();
            foreach ($value->detail as $key => $value2) {
                $value2->detailMaterial = BarangDatangDetailMaterial::where('barangDatangDetailId', $value2->id)->get();
            }
        }

        return view('bahanPembantu.supply.update')->with(['barangDatang'=>$barangDatang, 'data'=>$data]);
    }

    public function update($id, Request $request)
    {
        $data['purchaseId'] = $request['purchaseId'];
        $data['namaSuplier'] = $request['namaSuplier'];
        $data['total'] = 0;
        $data['userId'] = \Auth::user()->id;
        
        for ($i=0; $i < count($request['detailId']); $i++) { 
            $detailId = $request['detailId'][$i];
            $dataDetail['jumlah_datang'] = $request['qtySaatIni'][$detailId];

            $updateDetail = BarangDatangDetail::where('id',$detailId)->update($dataDetail);

            for ($j=0; $j < count($request['detailMaterialId'][$detailId]); $j++) {
                $detailMaterialId = $request['detailMaterialId'][$detailId][$j]; 
                $dt['gramasi'] = $request['gramasi'][$detailMaterialId];
                $dt['diameter'] = $request['diameter'][$detailMaterialId];
                $dt['brutto'] = $request['brutto'][$detailMaterialId];
                $dt['netto'] = $request['netto'][$detailMaterialId];
                $dt['tarra'] = $request['tarra'][$detailMaterialId];
                // $dt['unit'] = $request['unit'][$detailMaterialId][$j];
                $dt['userId'] = \Auth::user()->id;
                // $data['unitPrice'] = $request['unitPrice_'.$request['materialId'][$i]][$j];
                // $data['amount'] = $request['amount_'.$request['materialId'][$i]][$j];
                // $data['remark'] = $request['remark_'.$request['materialId'][$i]][$j];

                $updateDetailMaterial =  BarangDatangDetailMaterial::where('id',$detailMaterialId)->update($dt);
            } 
        }

        return redirect('bahan_baku/supply');

    }

    public function delete(Request $request)
    {
        $findGudang = GudangBahanBaku::find($request['gudangId']);
        $findGudangDetail = GudangBahanBakuDetail::where('gudangId',$request['gudangId'])->get();
        foreach ($findGudangDetail as $key => $value) {
            if(GudangBahanBakuDetailMaterial::where('gudangDetailId', $value->id)->delete() == null){
                break;
            }
        }
        $gudangDetail = GudangBahanBakuDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangBahanBaku::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('GBahanPembantu/supply');
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

    public function delDetailMaterial($id)
    {
        if(GudangBahanBakuDetailMaterial::where('id',$id)->delete()){
            return 1;
        }else{
            return 0;
        }

    }

    public function keluarGudang()
    {
        $data = [];
        $data[0] = GudangRajutKeluarDetail::groupBy('gdRajutKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();
        $data[1] = GudangCuciKeluarDetail::groupBy('gdCuciKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();
        $data[2] = GudangCompactKeluarDetail::groupBy('gdCompactKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();
        $data[3] = GudangInspeksiKeluarDetail::groupBy('gdInspeksiKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();
        $data[4] = GudangPotongKeluarDetail::groupBy('gdPotongKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();

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

                    case 4:
                        $val->gudangRequest = "Gudang Potong";
                        break;
                }
            }
        }

        // dd($data);

        return view('bahanPembantu.keluar.index')->with(['data'=>$data]);
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

        return view('bahanPembantu.keluar.create')->with(['dataMaterial'=>$dataMaterial]);
    }
    

    public function getDataMaterial($gudangRequest, $jenisKain = null)
    {
        $datas['material'] = MaterialModel::where('keterangan','LIKE', '%Bahan Bantu / Merchandise%')->get();
        foreach ($datas['material'] as $key => $value) {
            $data = GudangBahanBakuDetail::where('materialId',$value->id)->groupby('purchaseId')->get();
            $datas['purchase'] = [];
            foreach($data as $val){
                $datas['purchase'][] = $val->purchase;
            }
        }
            
        return json_encode($datas);
    }

    public function getDataPurchase($materialId)
    {
        $data['material'] = MaterialModel::find($materialId);
        if($data['material']){
            $datas = GudangBahanBakuDetail::where('materialId',$data['material']->id)->groupby('purchaseId')->get();
            $data['purchase'] = [];
            foreach($datas as $val){
                $data['purchase'][] = $val->purchase;
            }
        }
            
        return json_encode($data);
    }


    public function getDataGudang($materialId,$purchaseId)
    {
        $datas['diameter'] = [];
        $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
        $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('qty', '!=', 0)->get();
        
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
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->where('qty', '!=', 0)->get();
        
            foreach ($gudangMaterialDetail as $detail) {
                if (!in_array($detail->gramasi, $datas)) {
                    $datas[] = $detail->gramasi;
                }
            }
        }elseif ($berat == "null") {
            $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->where('gramasi', $gramasi)->where('qty', '!=', 0)->get();
        
            foreach ($gudangMaterialDetail as $detail) {
                if (!in_array($detail->netto, $datas)) {
                    $datas[] = $detail->netto;
                }
            }
        }else{
            $detailGudang = GudangBahanBakuDetail::where('materialId',$materialId)->where('purchaseId',$purchaseId)->first();
            $gudangMaterialDetail = GudangBahanBakuDetailMaterial::where('gudangDetailId', $detailGudang->id)->where('diameter', $diameter)->where('gramasi', $gramasi)->where('netto', $berat)->where('qty', '!=', 0)->first();
            
            $datas['gudangMaterialDetail'] = $gudangMaterialDetail->id;
            $datas['qty'] = $gudangMaterialDetail->qty;
        }       

        return json_encode($datas);
    }

    public function storeKeluarGudang(Request $request)
    {
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
            case 4:
                $gudang = 'Gudang Potong';
                $keluar = new GudangPotongKeluar;
                break;
            case 5:
                $gudang = 'Gudang Compact';
                $keluar = new GudangCompactKeluar;
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
                    if ($keluarDetail) {
                        $newQty = 0;
                        $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                        $newQty = $detailMaterial->netto - $request['qtyArr'][$i];
                        GudangBahanBakuDetailMaterial::detailMaterialUpdateField('netto', $newQty, $request['gudangMaterialDetailArr'][$i]);
                    }
                }elseif($request['jenisId'] == 2){
                    $keluarDetail = GudangCuciKeluarDetail::createGudangCuciKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                    if ($keluarDetail) {
                        $newQty = 0;
                        $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                        $newQty = $detailMaterial->qty - $request['qtyArr'][$i];
                        GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $newQty, $detailMaterial->id);
                        
                    }
                }elseif($request['jenisId'] == 3){
                    $keluarDetail = GudangInspeksiKeluarDetail::createGudangInspeksiKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                    if ($keluarDetail) {
                        $newQty = 0;
                        $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                        $newQty = $detailMaterial->qty - $request['qtyArr'][$i];
                        GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $newQty, $detailMaterial->id);
                        
                    }
                }elseif($request['jenisId'] == 4){
                    if ($request['jenisKain'] == 1) {
                        $keluarDetail = GudangPotongKeluarDetail::createGudangPotongKeluarDetail($gdId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], null, $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                        if ($keluarDetail) {
                            $newQty = 0;
                            $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                            $newQty = $detailMaterial->qty - $request['qtyArr'][$i];
                            GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $newQty, $detailMaterial->id);
                            
                        }
                    } else {
                        $keluarDetail = GudangPotongKeluarDetail::createGudangPotongKeluarDetail($gdId, $request['gudangIdArr'][$i], null, $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                        if ($keluarDetail) {
                            $newQty = 0;
                            $detailMaterial = GudangInspeksiStokOpname::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                            $newQty = $detailMaterial->qty - $request['qtyArr'][$i];
                            GudangInspeksiStokOpname::detailMaterialUpdateField('qty', $newQty, $detailMaterial->id);
                            
                        }
                    }
                    
                }elseif($request['jenisId'] == 5){
                    $keluarDetail = GudangCompactKeluarDetail::CreateCompactKeluarDetail($request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $gdId, $request['purchaseIdArr'][$i], $request['materialIdArr'][$i],$request['jenisIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                    if ($keluarDetail) {
                        $newQty = 0;
                        $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $request['gudangMaterialDetailArr'][$i])->first();
                        $newQty = $detailMaterial->qty - $request['qtyArr'][$i];
                        GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $newQty, $detailMaterial->id);
                        
                    }
                }
            }
        }

        return redirect('/GBahanPembantu/keluar');
    }

    public function detailKeluarGudang($id, $gudangRequest)
    {
        switch ($gudangRequest) {
            //KELUAR
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

            case 'Gudang Potong':
                $data = GudangPotongKeluar::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangPotongKeluarDetail::where('gdPotongKId',$id)->get();
                break;

            //MASUK
            case 'Gudang Rajut Masuk':
                $data = GudangRajutMasuk::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangRajutMasukDetail::where('gdRajutMId',$id)->get();
                break;

            case 'Gudang Compact Masuk':
                $data = GudangCompactMasuk::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangCompactMasukDetail::where('gdCompactMId',$id)->get();
                break;

            case 'Gudang Inspeksi Masuk':
                $data = GudangInspeksiMasuk::find($id);
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangInspeksiMasukDetail::where('gdInspeksiMId',$id)->get();
                break;
        }
        
        return view('bahanPembantu.keluar.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
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
        return view('bahanPembantu.keluar.update', ['data' => $data, 'dataDetail' => $dataDetail, 'gudangRequest' => $gudangRequest]);
    }

    public function updateSaveKeluarGudang(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                if($request['jenisId'] == 1){
                    $keluarDetail = GudangRajutKeluarDetail::createGudangRajutKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 2){
                    $keluarDetail = GudangCuciKeluarDetail::createGudangCuciKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }elseif($request['jenisId'] == 3){
                    $keluarDetail = GudangInspeksiKeluarDetail::createGudangInspeksiKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
                }
            }

            if ($keluarDetail == 1) {
                return redirect('/GBahanPembantu/keluar');
            }
        }else {
            return redirect('/GBahanPembantu/keluar');
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
            return redirect('GBahanPembantu/keluar/update/' . $gudangId . '/' . $gudangRequest . '');
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
                
        return redirect('GBahanPembantu/keluar');
    }

}
