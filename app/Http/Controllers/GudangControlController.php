<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangControlStokOpname;
use App\Models\GudangControlMasuk;
use App\Models\GudangControlMasukDetail;
use App\Models\GudangControlRekap;
use App\Models\GudangControlRekapDetail;
use App\Models\GudangJahitReject;
use App\Models\GudangJahitRejectDetail;
use App\Models\Pegawai;

use DB;

class GudangControlController extends Controller
{
    public function index()
    {
        $bajus = GudangControlStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangControlStokOpname::where('statusControl', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        return view('gudangControl.index', ['dataStok' => $dataStok]);
    }

    public function getData(Request $request)
    {
        $data = [];
        $i = 0;
        $purchaseId = GudangControlStokOpname::select('purchaseId')->where('jenisBaju', $request->jenisBaju)->groupBy('purchaseId')->get();

        foreach ($purchaseId as $detail) {
            if (!in_array($detail->purchaseId, $data)) {
                $data[$i]['purchaseId'] = $detail->purchaseId;
                $data[$i]['kode'] = $detail->purchase->kode;
                $i++;
            }
        }
        return json_encode($data);
    }

    public function getPegawai(Request $request)
    {
        // dd($request);
        $data = [];

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangControlStokOpname::where('statusControl', 0)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangControlStokOpname::where('statusControl', 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            $reqOperatorId = []; 

            $checkId = [];   
            $index = 0;    
            $checkId[$index]['operatorReqId'] = [];
            $checkId[$index]['purchase'] = [];
            $checkId[$index]['jumlah'] = [];

            $gdRequestOperator = GudangControlStokOpname::where('purchaseId', $request->purchaseId)
                                                            ->where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)
                                                            ->where('statusControl', 0)                                                             
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
        // dd($request);
        $data = [];

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangControlStokOpname::where('statusControl', 1)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangControlStokOpname::where('statusControl', 1)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            // dd($request);
            $reqOperatorId = []; 

            $checkId = [];   
            // $index = 0;    
            // $checkId[$index]['operatorReqId'] = [];
            // $checkId[$index]['purchase'] = [];
            // $checkId[$index]['jumlah'] = [];

            $gdRequestOperator = GudangControlStokOpname::where('purchaseId', $request->purchaseId)
                                                            ->where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)
                                                            ->where('statusControl', 1)                                                             
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            $gdBatilReject = GudangJahitReject::where('gudangRequest', 'Gudang Control')->whereDate('tanggal', date('Y-m-d'))->first();
            if ($gdBatilReject != null) {
                $gdBatilRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdBatilReject->id)->get();
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



    
    public function gRequest()
    {
        $controlMasuk = GudangControlMasuk::all();

        return view('gudangControl.request.index', ['gdControlMasuk' => $controlMasuk]);
    }

    public function gRequestTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  
        $gudangControl = GudangControlMasuk::where('id',$id)->first();
        $gudangControlDetail = GudangControlMasukDetail::where('gdControlMId', $gudangControl->id)->get();
        
        $gudangControlTerima = GudangControlMasuk::updateStatusDiterima($id, $statusDiterima);

        if ($gudangControlTerima == 1) {
            foreach ($gudangControlDetail as $value) {
                $gdBajuStokOpname = GudangControlStokOpname::BatilStokOpnameCreate($value->gdBajuStokOpnameId, null, $value->purchaseId, $value->jenisBaju, $value->ukuranBaju, 0, \Auth::user()->id);
            }
            if ($gdBajuStokOpname == 1) {
                return redirect('GControl/request');
            }
        }
    }

    public function gRequestDetail($id)
    {
        $ControlMasuk = GudangControlMasuk::where('id', $id)->first();
        $ControlMasukDetail = GudangControlMasukDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdControlMId', $ControlMasuk->id)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangControl.request.detail', ['gdControlMasukDetail' => $ControlMasukDetail]);
    }

    public function gOperator()
    {
        $dataPemindahan = [];
        $i = 0;        
        $gdRequestOperator = GudangControlStokOpname::groupBy('jenisBaju', 'ukuranBaju')->whereDate('tanggal', date('Y-m-d'))->get();
        $gdControl = GudangControlStokOpname::where('statusControl', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        $gdJahitRekap = GudangControlRekap::orderBy('tanggal', 'DESC')->groupBy('tanggal')->get();
        $pindahan = GudangControlStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusControl', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($pindahan as $detail) {
            $jumlahBaju = 0;
            $checkBatilDetail = GudangControlMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            if ($checkBatilDetail == null) {
                $target = [$detail->jenisBaju, $detail->ukuranBaju];                
                if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) {  
                    if ($dataPemindahan != null && count(array_intersect($dataPemindahan[$i-1], $target)) == count($target)) {
                        $jumlahBaju = 1;
                    }        

                    if($jumlahBaju != 0){
                        $dataPemindahan[$i-1]['jumlahBaju'] += $jumlahBaju;
                    }else{
                        $dataPemindahan[$i] = [
                            'tanggal' => date('d F Y', strtotime($detail->created_at)),
                            'jenisBaju' => $detail->jenisBaju,
                            'ukuranBaju' => $detail->ukuranBaju,
                            'jumlahBaju' => 1,
                        ];
                        $i++;
                    }
                }
            }
        }

        $gdSetrikaMasuk = NULL;
        // $gdControlMasuk = GudangControlMasuk::where('tanggal', date('Y-m-d'))->first();
        // if ($gdControlMasuk != null) {
        //     $gdControlMasukDetail = GudangControlMasukDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdControlMId', $gdControlMasuk->id)->groupBy('gdControlMId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        // }else {
        //     $gdControlMasukDetail = null;
        // }

        return view('gudangControl.operator.index', ['operatorRequest' => $gdRequestOperator, 'gdControl' => $gdControl, 'batilRekap' => $gdJahitRekap, 'dataPemindahan' => $pindahan, 'gdControlMasuk' => $gdSetrikaMasuk]);
    }

    public function gOperatorDataMaterial($purchaseId, $jenisBaju, $ukuranBaju, $jumlahBaju)
    {
        $data = [];
        if ($ukuranBaju == "null") {
            $gdRequestOperator = GudangControlStokOpname::select('ukuranBaju')->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->groupBy('ukuranBaju')->get();
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->ukuranBaju, $data)) {
                    $data[] = $operator->ukuranBaju;
                }
            }
        } else {
            $gdRequestOperator = GudangControlStokOpname::where('tanggal', null)->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('statusControl', 0)->get();
            if ($jumlahBaju == "null") {
                $jumlahBaju = count($gdRequestOperator);
            }

            $data['operatorReqId'] = [];
            $data['jumlah'] = $jumlahBaju;
            $i = 0;

            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->id, $data['operatorReqId']) && $i < $jumlahBaju) {
                    $data['operatorReqId'][$i] = $operator->id;
                    $i++;
                }
            }
            
        }

        return json_encode($data);
    }

    public function gOperatorDetail($date)
    {
        $gdRequestOperator = GudangControlStokOpname::where('tanggal', $date)->get();

        return view('gudangControl.operator.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorCreate()
    {
        $gdRequestOperator = GudangControlStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();

        return view('gudangControl.operator.create', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangControlStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangControlStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GControl/operator');
            }
        }else{
            return redirect('GControl/operator/create');
        }
    }

    public function gOperatorupdate($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangControlStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        $gdControl = GudangControlStokOpname::where('statusControl', 1)->whereDate('tanggal', date('Y-m-d'))->first();
        
        return view('gudangControl.operator.update', ['operatorRequest' => $gdRequestOperator, 'gdControl' => $gdControl, 'jenisBaju' => $jenisBaju]);
    }

    public function gOperatorupdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangControlStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangControlStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GControl/operator');
            }
        }else{
            return redirect('GControl/operator');
        }
    }

    public function gOperatorUpdateDelete($purchaseId, $jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangControlStokOpname::where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangControlStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GControl/operator/update/' . $jenisBaju . '/'. $ukuranBaju);
        }
    }

    public function gOperatorDelete(Request $request)
    {
        $gdRequestOperator = GudangControlStokOpname::where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangControlStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GControl/operator');
        }
    }



    public function gRekapCreate()
    {
        $pegawai = Pegawai::where('kodeBagian', 'control')->get();
        $purchaseId = GudangControlStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangControl.rekap.create', ['pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlRekap::where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $controlRekap = GudangControlRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $controlRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangControlStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangControlStokOpname::bajuUpdateField('statusControl', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $controlRekapDetail = GudangControlRekapDetail::ControlRekapDetailCreate($controlRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($controlRekapDetail == 1) {
                return redirect('GControl/operator');
            }
        } else {
            return redirect('GControl/rekap/create');
        }
    }

    public function gRekapDetail($id)
    {
        $getPegawai = GudangControlRekap::where('id', $id)->first();
        $getDetailPegawai = GudangControlRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdControlRekapId', $getPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangControl.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id)
    {
        $pegawai = Pegawai::where('kodeBagian', 'control')->get();
        $purchaseId = GudangControlStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        $getRekapanPegawai = GudangControlRekap::where('id', $id)->first();
        $getDetailRekapanPegawai = GudangControlRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdControlRekapId', $getRekapanPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangControl.rekap.update', ['id' => $getRekapanPegawai->id, 'rekapanPegawai' => $getDetailRekapanPegawai, 'pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapUpdateSave(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlRekap::where('id', $request->id)->first();
                if ($checkPegawai == null) {
                    $batilRekap = GudangControlRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $batilRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangControlStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangControlStokOpname::bajuUpdateField('statusControl', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $batilRekapDetail = GudangControlRekapDetail::ControlRekapDetailCreate($batilRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GControl/operator');
            }
        } else {
            return redirect('GControl/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $rekapDetailId)
    {
        $getDetailPegawai = GudangControlRekapDetail::where('id', $rekapDetailId)->first();

        $getPegawai = GudangControlRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangControlRekapDetail::where('gdControlRekapId', $getPegawai->id)->get();

        $operatorReq = GudangControlStokOpname::where('gdBajuStokOpnameId', $getDetailPegawai->gdBajuStokOpnameId)->first();
        if ($operatorReq != null) {
            $operatorReqUpdate = GudangControlStokOpname::bajuUpdateField('statusControl', 0, $operatorReq->id);
            if ($operatorReqUpdate == 1) {
                $deleteDetailPegawai = GudangControlRekapDetail::where('id', $rekapDetailId)->delete();
                if (count($CheckPegawai) == 1) {
                    $deletePegawai = GudangControlRekap::where('id', $rekapId)->delete();

                    if ($deletePegawai) {
                        return redirect('GControl/operator');
                    }
                }

                if ($deleteDetailPegawai) {
                    return redirect('GControl/rekap/update/' . $rekapId . '');
                }
                
            }                    
        } 
    }



    public function gKeluarCreate()
    {
        $dataPemindahan = [];
        $gdKeluarBatil = GudangControlStokOpname::where('statusControl', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdKeluarBatil as $detail) {
            if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) { 
                $dataPemindahan[] = [
                    'tanggal' => date('d F Y', strtotime($detail->tanggal)),
                    'gdBajuStokOpnameId' => $detail->gdBajuStokOpnameId,
                    'purchaseId' => $detail->purchaseId,
                    'purchase' => $detail->purchase->kode,
                    'jenisBaju' => $detail->jenisBaju,
                    'ukuranBaju' => $detail->ukuranBaju,
                    'statusControl' => $detail->statusControl,
                ];
            }            
        }

        for ($i=0; $i < count($dataPemindahan); $i++) { 
            $gdRekapDetail = GudangControlRekapDetail::where('gdBajuStokOpnameId', $dataPemindahan[$i]['gdBajuStokOpnameId'])->get();
            foreach ($gdRekapDetail as $detail) {
                $dataPemindahan[$i]['controlName'] = $detail->pegawai->nama;                
            }
        }

        return view('gudangControl.keluar.create', ['gdKeluarControl' => $dataPemindahan]);
    }

    public function gKeluarStore(Request $request)
    {
        if (count($request->gdBajuStokOpnameId) != 0) {
            $checkBatilMasuk = GudangControlMasuk::where('tanggal', date('Y-m-d'))->first();
            if ($checkBatilMasuk != null) {
                $batilMasuk = $checkBatilMasuk->id;
            }else {
                $batilMasuk = GudangControlMasuk::createControlMasuk();
            }

            for ($i=0; $i < count($request->gdBajuStokOpnameId); $i++) { 
                $batilMasukDetail = GudangControlMasukDetail::createGudangControlMasukDetail($batilMasuk, $request['gdBajuStokOpnameId'][$i], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
            }

            if ($batilMasukDetail == 1) {
                return redirect('GControl/operator');
            }
        } else {
            return redirect('GControl/operator');
        }
    }

    public function gKeluarDetail($date)
    {
        $dataPemindahan = [];
        $gdKeluarBatil = GudangControlStokOpname::where('tanggal', $date)->get();
        foreach ($gdKeluarBatil as $detail) {
            if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) { 
                $dataPemindahan[] = [
                    'tanggal' => date('d F Y', strtotime($detail->tanggal)),
                    'gdBajuStokOpnameId' => $detail->gdBajuStokOpnameId,
                    'purchase' => $detail->purchase->kode,
                    'jenisBaju' => $detail->jenisBaju,
                    'ukuranBaju' => $detail->ukuranBaju,
                    'statusControl' => $detail->statusControl,
                ];
            }            
        }

        for ($i=0; $i < count($dataPemindahan); $i++) { 
            $gdRekapDetail = GudangControlRekapDetail::where('gdBajuStokOpnameId', $dataPemindahan[$i]['gdBajuStokOpnameId'])->get();
            foreach ($gdRekapDetail as $detail) {
                $dataPemindahan[$i]['batilName'] = $detail->pegawai->nama;                
            }
        }

        return view('gudangControl.keluar.detail', ['gdKeluarBatil' => $dataPemindahan]);

    }



    public function gReject()
    {
        $gdJahitReject = GudangJahitReject::where('gudangRequest', 'Gudang Control')->get();

        return view('gudangControl.reject.index', ['jahitReject' => $gdJahitReject]);
    }

    public function gRejectTJCreate()
    {
        $purchaseId = GudangControlStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangControl.reject.create', ['purchases' => $purchaseId]);

    }

    public function gRejectTJStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangJahitReject::where('gudangRequest', 'Gudang Control')->where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $batilReject = GudangJahitReject::CreateGudangJahitReject('Gudang Control', date('Y-m-d'), $request['jumlahBaju'][$i], \Auth::user()->id);
                } else {
                    $batilReject = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $batilRekapDetail = GudangJahitRejectDetail::createGudangJahitRejectDetail($batilReject, $operatorReqId[$j], $request['keterangan'][$i]);
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GControl/reject');
            }
        } else {
            return redirect('GControl/reject/create');
        }
    }

    public function gRejectTJDetail($id)
    {
        $gdJahitReject = GudangJahitReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdJahitReject->id)->get();

        return view('gudangControl.reject.detail', ['jahitRejectDetail' => $gdJahitRejectDetail]);
    }

    public function gRejectTJUpdate($id)
    {
        $purchaseId = [];
        $i = 0;
        $checkPurchaseId = GudangControlStokOpname::whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();
        foreach ($checkPurchaseId as $purchase) {
            $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdBajuStokOpnameId', $purchase->id)->whereDate('created_at', date('Y-m-d'))->first();
            if ($gdJahitRejectDetail == null) {
                if (!in_array($purchase->purchaseId, $purchaseId)) {
                    $purchaseId[$i]['purchaseId'] = $purchase->purchaseId;
                    $purchaseId[$i]['kodePurchase'] = $purchase->purchase->kode;
                }
            }
        }
        $gdJahitReject = GudangJahitReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdJahitReject->id)->get();
        foreach ($gdJahitRejectDetail as $detail) {
            $checkBaju = GudangControlStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            $detail->kodePurchase = $checkBaju->purchase->kode;
            $detail->jenisBaju = $checkBaju->jenisBaju;
            $detail->ukuranBaju = $checkBaju->ukuranBaju;
            if ($detail->keterangan == null) {
                $detail->keterangan = " - ";
            }
        }
        return view('gudangControl.reject.update', ['purchases' => $purchaseId, 'gdJahitReject' => $gdJahitReject, 'jahitRejectDetail' => $gdJahitRejectDetail]);
    }

    public function gRejectTJUpdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangJahitReject::where('id', $request->id)->first();
                
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $batilRekapDetail = GudangJahitRejectDetail::createGudangJahitRejectDetail($checkPegawai->id, $operatorReqId[$j], $request['keterangan'][$i]);
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GControl/reject');
            }
        } else {
            return redirect('GControl/reject/create');
        }
    }

    public function gRejectTJUpdateDelete($rejectId, $detailRejectId)
    {
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('id', $detailRejectId)->delete();
        if ($gdJahitRejectDetail) {
            return redirect('GControl/reject/update/'.$rejectId);
        }else{
            return redirect('GControl/reject/update/' . $rejectId . '');
        }

    }

    public function gRejectTJDelete(Request $request)
    {
        // dd($request);
        $batilReject = GudangJahitReject::where('id', $request->rejectId)->first();
        $batilRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $batilReject->id)->get();
        if (count($batilRejectDetail) != 0) {
            $batilRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $batilReject->id)->delete();
            if ($batilRejectDetail) {
                GudangJahitReject::where('id', $request->rejectId)->delete();
                return redirect('GControl/reject');
            }
        }else{
            GudangJahitReject::where('id', $request->rejectId)->delete();
            return redirect('GControl/reject');
        }

    }
}
