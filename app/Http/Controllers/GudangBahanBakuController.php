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

use DB;
class GudangBahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $materials = MaterialModel::where('keterangan','LIKE','%Bahan Baku%')->get();
        $dataStok=[];
        foreach ($materials as $key => $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
            if ($material->id == 2 || $material->id == 3) {
                $dataStok[$material->id]['satuan'] = "Roll";
            }else {
                $dataStok[$material->id]['satuan'] = $material->satuan;
            }

            $data = GudangBahanBakuDetail::where('materialId', $material->id)->get();
            foreach ($data as $value) {
                $dataMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId', $value->id)->get();
                foreach ($dataMaterial as $detail) {
                    if ($material->id == 1) {
                        $dataStok[$value->materialId]['qty'] = ($dataStok[$value->materialId]['qty'] + $detail->netto)/181.44;
                    } else {
                        $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $detail->qty;
                    }
                    
                }
            }
        }
        
        $dataMasuk = GudangRajutMasuk::count() + GudangCompactMasuk::count() + GudangInspeksiMasuk::count();
        $dataKeluar = GudangRajutKeluar::count() + GudangCuciKeluar::count() +GudangCompactKeluar::count() + GudangInspeksiKeluar::count();
        return view('bahanBaku.index')->with(['dataStok'=>$dataStok,'dataMasuk'=>$dataMasuk,'dataKeluar'=>$dataKeluar]);
    }

    public function indexSupplyBarang(){
        $data = BarangDatangDetail::groupBy('purchaseId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();
        return view('bahanBaku.supply.index')->with(['data'=>$data]);
    }

    public function create()
    {
        $dtPurchase = [];
        $purchaseIds = AdminPurchase::where('jenisPurchase', "Purchase Order")->groupBy('kode')->get();
        
        foreach ($purchaseIds as $purchase) {            
            $jumlahClear = False;
            $datangDetail = BarangDatangDetail::select('purchaseId', 'materialId', 'qtyPermintaan', DB::raw('sum(jumlah_datang) as jumlah_datang'))
                                                ->where('purchaseId', $purchase->id)
                                                ->groupBy('purchaseId', 'materialId')
                                                ->get();
            foreach ($datangDetail as $detail) {
                if ($detail->qtyPermintaan <= (int)$detail->jumlah_datang) {
                    $jumlahClear = True;
                }else {
                    $jumlahClear = False;
                }
            }

            if ($jumlahClear == True) {
                $dtPurchase[] = $purchase->id;
            }
        }

        // dd($dtPurchase);
        $purchases = AdminPurchaseDetail::groupBy('purchaseId')
                            ->whereHas('material', function (Builder $query) {
                             $query->where('keterangan','LIKE', '%Bahan Baku%');
                            })
                            ->whereHas('purchase', function (Builder $query) {
                             $query->where('jenisPurchase', 'Purchase Order');
                            })
                            ->whereNotIn('purchaseId', $dtPurchase)
                            ->get();

        return view('bahanBaku.supply.create')->with(['purchases'=>$purchases]);
    }

    public function store(Request $request)
    {
        $jumlahData = $request['jumlah_data'];

        $barangDatang = new BarangDatang;
        $barangDatang->purchaseId = $request['purchaseId'];
        $barangDatang->namaSuplier = $request['suplier'];
            
        if($barangDatang->save()){
            //cek purchaseId sudah ada di gudang bahan baku atau belum
            $find = GudangBahanBaku::where('purchaseId',$request['purchaseId'])->first();
            if($find == null){
                $bahanBaku = new GudangBahanBaku;
                $bahanBaku->datangId = $barangDatang->id;
                $bahanBaku->purchaseId = $request['purchaseId'];
                $bahanBaku->namaSuplier = $request['suplier'];
                $bahanBaku->total = 0;
                $bahanBaku->userId = \Auth::user()->id;
                $bahanBaku->save();
                $gudangId = $bahanBaku->id;
            }else{
                $gudangId = $find->id;
            }

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

                $bahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$gudangId)
                                                        ->where('materialId',$materialId)
                                                        ->where('purchaseId',$request['purchaseId'])->first();

                if($bahanBakuDetail){
                    $data['qtySaatIni'] = $bahanBakuDetail->qtySaatIni + $request['qtySaatIni'][$i];
                    $updateBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$gudangId)
                                                        ->where('materialId',$materialId)
                                                        ->where('purchaseId',$request['purchaseId'])->update($data);

                }else{
                    $bahanBakuDetail = new GudangBahanBakuDetail;
                    $bahanBakuDetail->gudangId = $gudangId;
                    $bahanBakuDetail->datangDetailId = $barangDatangDetail->id;
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
                    $barangDatangMaterial = BarangDatangDetailMaterial::createDetailMaterial($barangDatangDetail->id, $diameter[0], $gramasi[0], $brutto[0], $netto[0], $tarra[0], $request['unit'][$i], $request['unitPrice'][$i], $request['amount'][$i], $request['remark'][$i]);

                    $bahanBakuDetailMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanBakuDetail->id)->first();

                    if($bahanBakuDetailMaterial){
                        $dataDetail['gramasi'] = $bahanBakuDetailMaterial->gramasi + $gramasi[0];
                        $dataDetail['diameter'] = $bahanBakuDetailMaterial->diameter + $diameter[0];
                        $dataDetail['brutto'] = $bahanBakuDetailMaterial->brutto + $brutto[0];
                        $dataDetail['netto'] = $bahanBakuDetailMaterial->netto + $netto[0];
                        $dataDetail['tarra'] = $bahanBakuDetailMaterial->tarra + $tarra[0];
                        $dataDetail['qty'] = $bahanBakuDetailMaterial->qty + 1;

                        $updateBahanBakuDetailMaterial =  GudangBahanBakuDetailMaterial::where('gudangDetailId',$bahanBakuDetail->id)->update($dataDetail);
                    }else{
                        $bahanBakuDetailMaterial = new GudangBahanBakuDetailMaterial;
                        $bahanBakuDetailMaterial->gudangDetailId = $bahanBakuDetail->id;
                        $bahanBakuDetailMaterial->detailMaterialId = $barangDatangMaterial;
                        $bahanBakuDetailMaterial->diameter = $diameter[0];
                        $bahanBakuDetailMaterial->gramasi = $gramasi[0];
                        $bahanBakuDetailMaterial->brutto = $brutto[0];
                        $bahanBakuDetailMaterial->netto = $netto[0];
                        $bahanBakuDetailMaterial->tarra = $tarra[0];
                        $bahanBakuDetailMaterial->qty = 1;
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
                        $barangDatangMaterial = BarangDatangDetailMaterial::createDetailMaterial($barangDatangDetail->id, $diameter[$j], $gramasi[$j], $brutto[$j], $netto[$j], $tarra[$j], $request['unit'][$i], $request['unitPrice'][$i], $request['amount'][$i], $request['remark'][$i]);
                        
                        $bahanBakuDetailMaterial = new GudangBahanBakuDetailMaterial;
                        $bahanBakuDetailMaterial->gudangDetailId = $bahanBakuDetail->id;
                        $bahanBakuDetailMaterial->detailMaterialId = $barangDatangMaterial;
                        $bahanBakuDetailMaterial->diameter = $diameter[$j];
                        $bahanBakuDetailMaterial->gramasi = $gramasi[$j];
                        $bahanBakuDetailMaterial->brutto = $brutto[$j];
                        $bahanBakuDetailMaterial->netto = $netto[$j];
                        $bahanBakuDetailMaterial->tarra = $tarra[$j];
                        $bahanBakuDetailMaterial->qty = 1;
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
        $barangDatang = BarangDatang::where('purchaseId',$id)->get();
        $data = $barangDatang->first();
        foreach ($barangDatang as $key => $value) {
            $value->detail = BarangDatangDetail::where('barangDatangId', $value->id)->get();
            foreach ($value->detail as $key => $value2) {
                $value2->detailMaterial = BarangDatangDetailMaterial::select('*', DB::raw('count(id) as jumlahRoll'))->where('barangDatangDetailId', $value2->id)->groupBy('diameter', 'gramasi', 'brutto', 'netto', 'tarra')->get();

                if ($value2->materialId == 1) {
                    $value2->rollTotal = "-";
                }else {
                    $value2->rollTotal = count(BarangDatangDetailMaterial::where('barangDatangDetailId', $value2->id)->get())." Roll";
                }
            }
        }

        return view('bahanBaku.supply.detail')->with(['barangDatang'=>$barangDatang, 'data'=>$data]);
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

        return view('bahanBaku.supply.update')->with(['barangDatang'=>$barangDatang, 'data'=>$data]);
    }

    public function update($id, Request $request)
    {
        // dd($request);
        $data['purchaseId'] = $request['purchaseId'];
        $data['namaSuplier'] = $request['namaSuplier'];
        $data['total'] = 0;
        $data['userId'] = \Auth::user()->id;
        
        for ($i=0; $i < count($request['detailId']); $i++) { 
            $detailId = $request['detailId'][$i];
            $dataDetail['jumlah_datang'] = $request['qtySaatIni'][$detailId];
            BarangDatangDetail::where('id',$detailId)->update($dataDetail);
            
            if (!empty($request['detailMaterialId'][$detailId])) {
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
    
                    BarangDatangDetailMaterial::where('id',$detailMaterialId)->update($dt);
                    GudangBahanBakuDetailMaterial::where('detailMaterialId',$detailMaterialId)->update($dt);
    
                }
            } 
        }

        return redirect('bahan_baku/supply');

    }   

    public function delete(Request $request)
    {
        // dd($request);
        $findBahanBaku = GudangBahanBaku::where('datangId', $request['barangDatangId'])->first();
        if ($findBahanBaku != Null) {
            $findBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$findBahanBaku->id)->get();

            foreach ($findBahanBakuDetail as $bahanBakuDetail) {     
                if(GudangBahanBakuDetailMaterial::where('gudangDetailId', $bahanBakuDetail->id)->delete() == null){
                    break;
                }
            }

            $findBahanBakuDetail = GudangBahanBakuDetail::where('gudangId',$findBahanBaku->id)->delete();
            if ($findBahanBakuDetail) {
                GudangBahanBaku::where('datangId', $request['barangDatangId'])->delete();
            }
        }

        $findBarangDatang = BarangDatang::where('purchaseId', $request['purchaseId'])->get();
        foreach ($findBarangDatang as $barangDatang) {
            $findBarangDatangDetail = BarangDatangDetail::where('barangDatangId',$barangDatang->id)->get();
            // dd($findBarangDatangDetail);

            foreach ($findBarangDatangDetail as $barangDetail) {     
                if(BarangDatangDetailMaterial::where('barangDatangDetailId', $barangDetail->id)->delete() == null){
                    break;
                }
            }

            $findBarangDatangDetail = BarangDatangDetail::where('barangDatangId',$barangDatang->id)->delete();
            if ($findBarangDatangDetail) {
                BarangDatang::where('purchaseId', $request['purchaseId'])->delete();
            }
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

    public function delDetailMaterial($id)
    {
        $BahanBakuMaterial = GudangBahanBakuDetailMaterial::where('detailMaterialId',$id)->delete();
        if($BahanBakuMaterial){
            BarangDatangDetailMaterial::where('id',$id)->delete();
            return 1;
        }else{
            return 0;
        }

    }

    public function keluarGudang()
    {
        $data = [];
        $data[0] = GudangRajutKeluarDetail::groupBy('gdRajutKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();
        $data[1] = GudangCuciKeluarDetail::groupBy('gdCuciKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();
        $data[2] = GudangCompactKeluarDetail::groupBy('gdCompactKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();
        $data[3] = GudangInspeksiKeluarDetail::groupBy('gdInspeksiKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();
        $data[4] = GudangPotongKeluarDetail::groupBy('gdPotongKId')->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Baku%');
                    })->get();

        for ($i=0; $i < count($data); $i++) { 
            foreach ($data[$i] as $val) {
                switch ($i) {
                    case 0:
                        $val->gudangRequest = "Gudang Rajut";
                        $val->statusDiterima = $val->rajut->statusDiterima;
                        break;
                    
                    case 1:
                        $val->gudangRequest = "Gudang Cuci";
                        $val->statusDiterima = $val->cuci->statusDiterima;
                        $dataCompact = GudangCompactKeluar::where('gdCuciKId',$val->id)->first();
                        if ($dataCompact != null) {
                            $val->cuciDelete = false;
                        }
                        break;

                    case 2:
                        $val->gudangRequest = "Gudang Compact";
                        $val->statusDiterima = $val->compact->statusDiterima;

                        break;

                    case 3:
                        $val->gudangRequest = "Gudang Inspeksi";
                        $val->statusDiterima = $val->inspeksi->statusDiterima;

                        break;

                    case 4:
                        $val->gudangRequest = "Gudang Potong";
                        $val->statusDiterima = $val->gdPotongKeluar->statusDiterima;

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
    

    public function getDataMaterial($gudangRequest, $jenisKain = null)
    {
        if ($gudangRequest != 4 || ($gudangRequest == 4 && $jenisKain == 1)) {
            if ($gudangRequest == 4) {
                $gudangRequest = 3;
            }
            $data['material'] = MaterialModel::where('jenisId',$gudangRequest)->first();
            $datas = GudangBahanBakuDetail::where('materialId',$data['material']->id)->groupby('purchaseId')->get();
            $data['purchase'] = [];
            foreach($datas as $val){
                $dataMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId', $val->id)->get();
                foreach ($dataMaterial as $dataM) {
                    if ($data['material']->id == 1 && $dataM->netto != 0) {
                        if (!in_array($val->purchase, $data['purchase'])) {
                            $data['purchase'][] = $val->purchase;
                        }
                    }elseif ($data['material']->id != 1 && $dataM->qty != 0) {
                        if (!in_array($val->purchase, $data['purchase'])) {
                            $data['purchase'][] = $val->purchase;
                        }
                    }
                }
                
            }
        }else{
            $data['material'] = MaterialModel::where('jenisId',3)->first();
            $datas = GudangInspeksiStokOpname::where('materialId', $data['material']->id)->groupby('purchaseId')->get();
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

    public function getDataGudangInspeksi($materialId,$purchaseId)
    {
        $datas['diameter'] = [];
        $detailGudangInspeksi = gudangInspeksiStokOpname::where('materialId',$materialId)->where('purchaseId',$purchaseId)->where('qty','!=',0)->get();
        
        foreach ($detailGudangInspeksi as $detail) {
            $datas['gudangId'] = $detail->gudangDetailmaterial->bahanBakuDetail->gudangId;
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

    public function getDataDetailMaterialInspeksi($materialId, $purchaseId, $diameter, $gramasi="", $berat="")
    {
        $datas = [];
        if ($gramasi == "null") {
            $detailGudang = gudangInspeksiStokOpname::where('materialId',$materialId)->where('purchaseId',$purchaseId)->where('diameter', $diameter)->where('qty', '!=', 0)->get();
        
            foreach ($detailGudang as $detail) {
                if (!in_array($detail->gramasi, $datas)) {
                    $datas[] = $detail->gramasi;
                }
            }
        }elseif ($berat == "null") {
            $detailGudang = gudangInspeksiStokOpname::where('materialId',$materialId)->where('purchaseId',$purchaseId)->where('diameter', $diameter)->where('gramasi', $gramasi)->where('qty', '!=', 0)->get();
            foreach ($detailGudang as $detail) {
                $gudangMaterialDetail = gudangInspeksiStokOpnameDetail::where('gdInspeksiStokId', $detail->id)->get();
                foreach ($gudangMaterialDetail as $value) {
                    if (!in_array($value->berat, $datas)) {
                        $datas[] = $value->berat;
                    }
                }
            }
        }else{
            $detailGudang = gudangInspeksiStokOpname::where('materialId',$materialId)->where('purchaseId',$purchaseId)->where('diameter', $diameter)->where('gramasi', $gramasi)->where('qty', '!=', 0)->first();
            $gudangMaterialDetail = gudangInspeksiStokOpnameDetail::where('gdInspeksiStokId', $detailGudang->id)->where('berat', $berat)->first();
            
            $datas['gudangMaterialDetail'] = $gudangMaterialDetail->inspeksiStok->id;
            $datas['qty'] = $gudangMaterialDetail->inspeksiStok->qty;
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
                    
                }
            }
        }

        return redirect('/bahan_baku/keluar');
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

            case 'Gudang Potong':
                $data = GudangPotongKeluar::find($id);
                $data->gudangRequestId = 4;
                $data->gudangRequest = $gudangRequest;
                $dataDetail = GudangPotongKeluarDetail::where('gdPotongKId',$id)->get();
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
                    $keluarDetail = GudangCuciKeluarDetail::createGudangCuciKeluarDetail($request->gudangKeluarId, $request['gudangIdArr'][$i], $request['gudangMaterialDetailArr'][$i], $request['purchaseIdArr'][$i], $request['materialIdArr'][$i], $request['gramasiArr'][$i], $request['diameterArr'][$i], $request['beratArr'][$i], $request['qtyArr'][$i]);
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

            case 'Gudang Potong':
                $dataDetail = GudangPotongKeluarDetail::where('id',$detailId)->delete();
                break;
        }
        if ($dataDetail) {
            return redirect('bahan_baku/keluar/update/' . $gudangId . '/' . $gudangRequest . '');
        }
    }

    public function deleteKeluarGudang(Request $request)
    {
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

            case 'Gudang Potong':
                $dataDetail = GudangPotongKeluarDetail::where('gdPotongKId',$request->gudangRequestId)->get();
                $dataDetailDelete = GudangPotongKeluarDetail::where('gdPotongKId',$request->gudangRequestId)->delete();
                if($dataDetailDelete != 0){
                    if ($dataDetailDelete) {
                        GudangPotongKeluar::where('id', $request->gudangRequestId)->delete();
                    }
                }else {
                    GudangPotongKeluar::where('id', $request->gudangRequestId)->delete();
                }
                break;
        }
        
        if ($dataDetailDelete) {
            if ($request->gudangRequestName == "Gudang Rajut") {
                foreach ($dataDetail as $value) {
                    $getStok = GudangBahanBakuDetailMaterial::where('id', $value->gudang->bahanBakuDetail->bahanBakuDetailMaterial[0]->id)->first();
                    $qty = $getStok->netto + $value->qty;
                    $update = GudangBahanBakuDetailMaterial::detailMaterialUpdateField('netto', $qty, $getStok->id);
                }
            } else {
                foreach ($dataDetail as $value) {
                    $getStok = GudangBahanBakuDetailMaterial::where('id', $value->gdDetailMaterialId)->first();
                    $qty = $getStok->qty + $value->qty;
                    $update = GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $qty, $getStok->id);
                }
            } 
        }
                
        return redirect('bahan_baku/keluar');
    }

    public function masukGudang()
    {   
        $data = [];
        $data[0] = GudangRajutMasuk::all();
        $data[1] = GudangCompactMasuk::all();
        $data[2] = GudangInspeksiMasuk::all();

        for ($i=0; $i < count($data); $i++) { 
            foreach ($data[$i] as $val) {
                switch ($i) {
                    case 0:
                        $val->gudangRequest = "Gudang Rajut Masuk";
                        break;
                    
                    case 1:
                        $val->gudangRequest = "Gudang Compact Masuk";
                        break;

                    case 2:
                        $val->gudangRequest = "Gudang Inspeksi Masuk";
                        break;
                }
            }
        }
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

    public function terimaMasukGudang($id, $gudangRequest){

        // dd($id, $gudangRequest);

        switch ($gudangRequest) {
            case 'Gudang Rajut Masuk':
                $data = GudangRajutMasuk::where('id',$id)->first();
                $dataDetail = GudangRajutMasukDetail::where('gdRajutMId',$data->id)->get();
                $statusDiterima = 1; 
                $gudangTerima = GudangRajutMasuk::updateStatusDiterima($id, $statusDiterima);
                break;

            case 'Gudang Compact Masuk':
                $data = GudangCompactMasuk::where('id',$id)->first();
                $dataDetail = GudangCompactMasukDetail::where('gdCompactMId',$data->id)->get();
                $statusDiterima = 1; 
                $gudangTerima = GudangCompactMasuk::updateStatusDiterima($id, $statusDiterima);
                break;

            case 'Gudang Inspeksi Masuk':
                $data = GudangInspeksiMasuk::where('id',$id)->first();
                $dataDetail = GudangInspeksiMasukDetail::where('gdInspeksiMId',$data->id)->get();
                $statusDiterima = 1; 
                $gudangTerima = GudangInspeksiMasuk::updateStatusDiterima($id, $statusDiterima);
                break;
        } 

            
        if ($statusDiterima == 1) {
            if ($gudangRequest != "Gudang Inspeksi Masuk") {
                foreach ($dataDetail as $value) {
                    $gudangDetailMaterial = GudangBahanBakuDetailMaterial::where('id',$value->gdDetailMaterialId)->first();
                    $qty = $gudangDetailMaterial->qty + $value->qty;
    
                    GudangBahanBakuDetailMaterial::detailMaterialUpdateField('qty', $qty, $value->gdDetailMaterialId);
                }
            }
                
            return redirect('bahan_baku/masuk');
        }
    }

}
