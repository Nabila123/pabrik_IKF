<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangSetrikaStokOpname;
use App\Models\GudangPackingRekap;
use App\Models\GudangPackingRekapDetail;
use App\Models\GudangControlReject;
use App\Models\GudangControlRejectDetail;
use App\Models\GudangBarangJadiStokOpname;
use App\Models\Pegawai;
use DB;
use PDF;


class PackingController extends Controller
{
    public function index()
    {
        $bajus = GudangSetrikaStokOpname::select('jenisBaju')->where('statusSetrika', 1)->groupBy('jenisBaju')->get();
        $data = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }

        return view('packing.index', ['dataStok' => $dataStok]);
    }

    public function getPegawai(Request $request)
    {
        $data = [];

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 0)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            $reqOperatorId = []; 

            $checkId = [];   
            $index = 0;    
            $checkId[$index]['operatorReqId'] = [];
            $checkId[$index]['purchase'] = [];
            $checkId[$index]['jumlah'] = [];

            $gdRequestOperator = GudangSetrikaStokOpname::where('purchaseId', $request->purchaseId)
                                                            ->where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)
                                                            ->where('statusSetrika', 1)
                                                            ->where('statusPacking', 0)                                                             
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            if (isset($request->operatorReqPurchaseId)) {
                for ($i=0; $i < count($request['operatorReqPurchaseId']); $i++) { 
                    if ($request['operatorReqPurchaseId'][$i] == null) {
                        continue;
                    }                    
                    
                    if ($index != 0 && $request['operatorReqPurchaseId'][$i] == $checkId[$index-1]['purchase']) {
                        $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                        for ($j=0; $j < count($operatorReqId); $j++) { 
                            if (!in_array($operatorReqId[$j], $checkId[$index-1]['operatorReqId'])) {
                                $checkId[$index-1]['operatorReqId'][] = $operatorReqId[$j];
                                $checkId[$index-1]['jumlah'] += 1;
                            }            
                        }
                    }else {
                        $checkId[$index]['purchase'] = $request['operatorReqPurchaseId'][$i];
                        $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                        for ($j=0; $j < count($operatorReqId); $j++) { 
                            $checkId[$index]['operatorReqId'][] = $operatorReqId[$j];
                        }
                        $checkId[$index]['jumlah'] = count($operatorReqId);
                        $index++;
                    }    
                }
            }

            
            if (!isset($request->jumlahBaju)) {
                $request->jumlahBaju = count($gdRequestOperator);
            } 
            
            $i = 0;
            foreach ($gdRequestOperator as $operator) {
                if (isset($request->operatorReqPurchaseId)) {
                    $cek = false;
                    for ($j=0; $j < count($checkId); $j++) { 
                        if ($operator->purchaseId == $checkId[$j]['purchase']) {
                            $cek = true;
                            if (!isset($request->jumlahBaju)) {
                                $request->jumlahBaju -= $checkId[$j]['jumlah'];
                            }
                            
                            if (!in_array($operator->id, $checkId[$j]['operatorReqId'])) {
                                if (!in_array($operator->id, $reqOperatorId)) {
                                    if ($i < $request->jumlahBaju) {
                                        $reqOperatorId[$i] = $operator->id;
                                        $i++;                                    
                                    }                                   
                                }
                            }
                        }                        
                    }
                    if ($cek == false) {
                        if (!in_array($operator->id, $reqOperatorId) && $i < $request->jumlahBaju) {
                            $reqOperatorId[$i] = $operator->id;
                            $i++;
                        }
                    }
                } else {
                    if (!in_array($operator->id, $reqOperatorId) && $i < $request->jumlahBaju) {
                        $reqOperatorId[$i] = $operator->id;
                        $i++;
                    }
                }
            }            
        }        

        if (isset($gdRequestOperator)) {
            foreach ($gdRequestOperator as $operator) {
                if ($request->groupBy == "purchaseId") {
                    $data['operator'][] = [
                        'purchaseId' => $operator->purchaseId, 
                        'kodePurchase' => $operator->purchase->kode
                    ];
                }elseif ($request->groupBy == "jenisBaju") {
                    $data['operator'][] = [
                        'jenisBaju' => $operator->jenisBaju
                    ];
                }elseif ($request->groupBy == "ukuranBaju") {
                    $data['operator'][] = [
                        'ukuranBaju' => $operator->ukuranBaju
                    ];
                }
            }

            if ($request->groupBy == "id"){
                $data['operator'] = [
                    'requestOperatorId' => $reqOperatorId,
                    'jumlahBaju' => count($reqOperatorId)
                ];
            }
        }
        
        return json_encode($data);
    }

    public function getReject(Request $request)
    {
        $data = [];

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 1)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 1)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            // dd($request);
            $reqOperatorId = []; 

            $checkId = [];   
            // $index = 0;    
            // $checkId[$index]['operatorReqId'] = [];
            // $checkId[$index]['purchase'] = [];
            // $checkId[$index]['jumlah'] = [];

            $gdRequestOperator = GudangSetrikaStokOpname::where('purchaseId', $request->purchaseId)
                                                            ->where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)
                                                            ->where('statusSetrika', 1)
                                                            ->where('statusPacking', 1)                                                             
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            $gdBatilReject = GudangControlReject::where('gudangRequest', 'Gudang Packing')->whereDate('tanggal', date('Y-m-d'))->first();
            if ($gdBatilReject != null) {
                $gdBatilRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdBatilReject->id)->get();
                if ($gdBatilRejectDetail != null) {
                    foreach ($gdBatilRejectDetail as $detail) {
                        if (!in_array($detail->gdBajuStokOpnameId, $checkId)) {
                            $checkId[] = $detail->gdBajuStokOpnameId;
                        }
                    }
                }
            }
            
            if (!isset($request->jumlahBaju)) {
                $request->jumlahBaju = count($gdRequestOperator);
            } 
            
            $i = 0;            
            foreach ($gdRequestOperator as $operator) {
                // dd($operator->gdBajuStokOpnameId, $checkId);
                if (!in_array($operator->gdBajuStokOpnameId, $checkId)) {
                    if (!in_array($operator->gdBajuStokOpnameId, $reqOperatorId)) {
                        if ($i < $request->jumlahBaju) {
                            $reqOperatorId[$i] = $operator->gdBajuStokOpnameId;
                            $i++;                                    
                        }
                    }
                }
            }            
        }        

        if (isset($gdRequestOperator)) {
            foreach ($gdRequestOperator as $operator) {
                if ($request->groupBy == "purchaseId") {
                    $data['operator'][] = [
                        'purchaseId' => $operator->purchaseId, 
                        'kodePurchase' => $operator->purchase->kode
                    ];
                }elseif ($request->groupBy == "jenisBaju") {
                    $data['operator'][] = [
                        'jenisBaju' => $operator->jenisBaju
                    ];
                }elseif ($request->groupBy == "ukuranBaju") {
                    $data['operator'][] = [
                        'ukuranBaju' => $operator->ukuranBaju
                    ];
                }
            }

            if ($request->groupBy == "id"){
                $data['operator'] = [
                    'requestOperatorId' => $reqOperatorId,
                    'jumlahBaju' => count($reqOperatorId)
                ];
            }
        }
        
        return json_encode($data);
    }

    public function getKodePacking($kodePacking)
    {
        $GetKodePacking = GudangPackingRekap::kodePacking();
        $packingKode = explode(",", $kodePacking);
        for ($i=0; $i < count($packingKode); $i++) { 
            if ($GetKodePacking == $packingKode[$i]) {
                Self::getKodePacking($kodePacking);
            }
        }        

        return json_encode($GetKodePacking);
    }

    public function getKode(Request $request)
    {
        $data = [];
        $GetKodePacking = GudangBarangJadiStokOpname::where('kodeproduct', $request->kodeProduct)->first();
        if ($GetKodePacking == null) {
            $getPackingRekap = GudangPackingRekap::where('kodePacking', $request->kodeProduct)->first();
            $getPackingRekapDetail = GudangPackingRekapDetail::where('gdPackingRekapId', $getPackingRekap->id)->get();
            foreach ($getPackingRekapDetail as $detail) {
                $addBarangJadiStokOpname = GudangBarangJadiStokOpname::CreateGudangBarangStokOpname($detail->gdBajuStokOpnameId, $getPackingRekap->kodePacking, $detail->purchaseId, $detail->jenisBaju, $detail->ukuranBaju);
            }

            if($addBarangJadiStokOpname == 1){
                $getBarangJadi = GudangBarangJadiStokOpname::where('tanggal', date('Y-m-d'))->groupBy('kodeProduct')->get();
                foreach ($getBarangJadi as $value) {
                    $data[] = $value;
                }
                return json_encode($data);
            }

        }
    }

    public function gRequest()
    {
        $gdPackingRequest = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusPacking', 0)->where('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        // $gdPackingRequest = GudangSetrikaStokOpname::where('statusPacking', 0)->groupby('tanggal')->get();

        return view('packing.request.index', ['packingRequest' => $gdPackingRequest]);
    }

    public function gRequestDetail($date)
    {
        $gdPackingRequest = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusPacking', 0)->where('tanggal', $date)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('packing.request.detail', ['gdPackingRequestDetail' => $gdPackingRequest]);
    }

    public function gOperator()
    {
        $gdPackingRekap = GudangPackingRekap::where('tanggal', date('Y-m-d'))->get();
        $gdBarangJadi = GudangBarangJadiStokOpname::where('tanggal', date('Y-m-d'))->groupBy('kodeProduct')->get();
        foreach ($gdPackingRekap as $packing) {
            $gdPackingRekapDetail = GudangPackingRekapDetail::where('gdPackingRekapId', $packing->id)->groupBy('pegawaiId')->get();
            foreach ($gdPackingRekapDetail as $packingDetail) {
                $packing->pegawaiName = $packingDetail->pegawai->nama;
            }
        }

        return view('packing.operator.index', ['packingRekap' => $gdPackingRekap, 'barangJadi' => $gdBarangJadi]);
    }

    public function gRekapDetail($id)
    {
        $getPegawai = GudangPackingRekap::where('id', $id)->first();
        $getDetailPegawai = GudangPackingRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdPackingRekapId', $getPegawai->id)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('packing.operator.detail', ['rekap' => $getPegawai, 'pegawais' => $getDetailPegawai]);
    }

    public function gRekapCreate()
    {
        $pegawai = Pegawai::where('kodeBagian', 'bungkus')->get();
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->where('statusSetrika', 1)->where('statusPacking', 0)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();
        $kodePacking = GudangPackingRekap::kodePacking();

        return view('packing.operator.create', ['pegawais' => $pegawai, 'purchases' => $purchaseId, 'kodePacking' => $kodePacking]);
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $PackingRekap = GudangPackingRekap::PackingRekapCreate($request['kodePacking'][$i], \Auth::user()->id);     
                if ($PackingRekap) {
                    $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                    for ($j=0; $j < count($operatorReqId); $j++) {
                        $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                        if ($operatorReq != null) {
                            $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusPacking', 1, $operatorReq->id);
                            if ($bajuStokOpname == 1) {
                                $PackingRekapDetail = GudangPackingRekapDetail::PackingRekapDetailCreate($PackingRekap, $operatorReq->gdBajuStokOpnameId, $request['pegawaiId'][$i], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                            }              
                        } 
                    }
                }                                
            }   
            
            if ($PackingRekapDetail == 1) {
                return redirect('GPacking/operator');
            }
        } else {
            return redirect('GPacking/rekap/create');
        }
    }

    public function gRekapUpdate($id)
    {
        $pegawai = Pegawai::where('kodeBagian', 'bungkus')->get();
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        $getRekapanPegawai = GudangPackingRekap::where('id', $id)->first();
        $getDetailRekapanPegawai = GudangPackingRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdPackingRekapId', $getRekapanPegawai->id)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('packing.operator.update', ['rekap' => $getRekapanPegawai, 'rekapanPegawai' => $getDetailRekapanPegawai, 'pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapUpdateSave(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangPackingRekap::where('id', $request->id)->first();
                if ($checkPegawai == null) {
                    $PackingRekap = GudangPackingRekap::PackingRekapCreate($request['kodePacking'][$i], \Auth::user()->id);     
                } else {
                    $PackingRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusPacking', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $PackingRekapDetail = GudangPackingRekapDetail::PackingRekapDetailCreate($PackingRekap, $operatorReq->gdBajuStokOpnameId, $request['pegawaiId'][$i], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    }
                }                                             
            }

            if ($PackingRekapDetail == 1) {
                return redirect('GPacking/operator');
            }
        } else {
            return redirect('GPacking/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $rekapDetailId)
    {
        $getDetailPegawai = GudangPackingRekapDetail::where('id', $rekapDetailId)->first();

        $getPegawai = GudangPackingRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangPackingRekapDetail::where('gdPackingRekapId', $getPegawai->id)->get();

        $operatorReq = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $getDetailPegawai->gdBajuStokOpnameId)->first();
        if ($operatorReq != null) {
            $operatorReqUpdate = GudangSetrikaStokOpname::bajuUpdateField('statusPacking', 0, $operatorReq->id);
            if ($operatorReqUpdate == 1) {
                $deleteDetailPegawai = GudangPackingRekapDetail::where('id', $rekapDetailId)->delete();
                if (count($CheckPegawai) == 1) {
                    $deletePegawai = GudangPackingRekap::where('id', $rekapId)->delete();

                    if ($deletePegawai) {
                        return redirect('GPacking/operator');
                    }
                }

                if ($deleteDetailPegawai) {
                    return redirect('GPacking/rekap/update/' . $rekapId . '');
                }
                
            }                    
        } 
    }

    public function gRekapCetakBarcode()
    {
        $GetKodePacking = GudangPackingRekap::select('kodePacking')->whereDate('tanggal', date('Y-m-d'))->get();
        $data = [];
        $baris = 0;
        $i = 0;

        foreach ($GetKodePacking as $packing) {
            if ($i % 3 == 0) {
                $baris++;
            }

            $data[$baris][] = $packing->kodePacking;
            $i++;
        }

        $pdf = PDF::loadview('packing.operator.cetakBarcode',['data' => $data])
                ->setPaper('a4', 'potrait');
    	return $pdf->stream();

        // return view('packing.operator.cetakBarcode', ['data' => $data]);
    }

    public function gPackingBahanBakuCreate()
    {
        return view('packing.bahanBaku.create');
    }

    public function gPackingBahanBakuDelete(Request $request)
    {
        $data = [];
        $getBarangJadi = GudangBarangJadiStokOpname::where('kodeProduct', $request->kodeProduct)->delete();
        if ($getBarangJadi) {
            $getBarangJadi = GudangBarangJadiStokOpname::where('tanggal', date('Y-m-d'))->groupBy('kodeProduct')->get();
                foreach ($getBarangJadi as $value) {
                    $data[] = $value;
                }
            return json_encode($data);
        }
    }

    public function gPackingBahanBakuStore()
    {
        return redirect('GPacking/operator');
    }

    public function gReject()
    {
        $gdControlReject  = GudangControlReject::where('gudangRequest', 'Gudang Packing')->get();

        return view('packing.reject.index', ['gdControlReject' => $gdControlReject]);
    }

    public function gRejectTerima($id)
    {
        $id = $id;  
        $statusDiterima = 3;  
        
        $gudangPotongTerima = GudangControlReject::updateStatusDiterima('statusProses', $statusDiterima, $id);
        $gdJahitReject = GudangControlReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdJahitReject->id)->get();

        if ($gudangPotongTerima == 1) {
            foreach ($gdJahitRejectDetail as $detail) {
                $gdSetrika = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
                $updateStatusReject = GudangSetrikaStokOpname::bajuUpdateField('statusReject', 0, $gdSetrika->id);
            }

            if ($updateStatusReject == 1) {
                return redirect('GSetrika/reject');
            }
        }
    }

    public function gRejectCreate()
    {
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('packing.reject.create', ['purchases' => $purchaseId]);

    }

    public function gRejectStore(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlReject::where('gudangRequest', 'Gudang Packing')->where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $batilReject = GudangControlReject::CreateGudangControlReject('Gudang Packing', date('Y-m-d'), $request['jumlahBaju'][$i], \Auth::user()->id);
                } else {
                    $batilReject = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $batilRekapDetail = GudangControlRejectDetail::createGudangControlRejectDetail($batilReject, $operatorReqId[$j], $request['keterangan'][$i]);
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GPacking/reject');
            }
        } else {
            return redirect('GPacking/reject/create');
        }
    }

    public function gRejectDetail($id)
    {
        $gdJahitReject = GudangControlReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdJahitReject->id)->get();

        return view('packing.reject.detail', ['jahitRejectDetail' => $gdJahitRejectDetail]);
    }

    public function gRejectUpdate($id)
    {
        $purchaseId = [];
        $i = 0;
        $checkPurchaseId = GudangSetrikaStokOpname::whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($checkPurchaseId as $purchase) {
            $gdJahitRejectDetail = GudangControlRejectDetail::where('gdBajuStokOpnameId', $purchase->gdBajuStokOpnameId)->whereDate('created_at', date('Y-m-d'))->first();
            if ($gdJahitRejectDetail == null) {
                if (!in_array($purchase->purchaseId, $purchaseId)) {
                    $purchaseId[$i]['purchaseId'] = $purchase->purchaseId;
                    $purchaseId[$i]['kodePurchase'] = $purchase->purchase->kode;
                }
            }
        }
        $gdJahitReject = GudangControlReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdJahitReject->id)->get();
        foreach ($gdJahitRejectDetail as $detail) {
            $checkBaju = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            $detail->kodePurchase = $checkBaju->purchase->kode;
            $detail->jenisBaju = $checkBaju->jenisBaju;
            $detail->ukuranBaju = $checkBaju->ukuranBaju;
            if ($detail->keterangan == null) {
                $detail->keterangan = " - ";
            }
        }
        return view('packing.reject.update', ['purchases' => $purchaseId, 'gdJahitReject' => $gdJahitReject, 'jahitRejectDetail' => $gdJahitRejectDetail]);
    }

    public function gRejectUpdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlReject::where('id', $request->id)->first();
                
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $batilRekapDetail = GudangControlRejectDetail::CreateGudangControlRejectDetail($checkPegawai->id, $operatorReqId[$j], $request['keterangan'][$i]);
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GPacking/reject');
            }
        } else {
            return redirect('GPacking/reject/create');
        }
    }

    public function gRejectUpdateDelete($rejectId, $detailRejectId)
    {
        $gdJahitRejectDetail = GudangControlRejectDetail::where('id', $detailRejectId)->delete();
        if ($gdJahitRejectDetail) {
            return redirect('GPacking/reject/update/'.$rejectId);
        }else{
            return redirect('GPacking/reject/update/' . $rejectId . '');
        }

    }

    public function gRejectDelete(Request $request)
    {
        // dd($request);
        $batilReject = GudangControlReject::where('id', $request->rejectId)->first();
        $batilRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $batilReject->id)->get();
        if (count($batilRejectDetail) != 0) {
            $batilRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $batilReject->id)->delete();
            if ($batilRejectDetail) {
                GudangControlReject::where('id', $request->rejectId)->delete();
                return redirect('GPacking/reject');
            }
        }else{
            GudangControlReject::where('id', $request->rejectId)->delete();
            return redirect('GPacking/reject');
        }

    }
}
