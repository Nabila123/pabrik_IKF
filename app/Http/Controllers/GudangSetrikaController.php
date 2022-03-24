<?php

namespace App\Http\Setrikalers;

use Illuminate\Http\Request;
use App\Models\GudangSetrikaStokOpname;
use App\Models\GudangSetrikaMasuk;
use App\Models\GudangSetrikaMasukDetail;
use App\Models\GudangSetrikaRekap;
use App\Models\GudangSetrikaRekapDetail;
use App\Models\GudangSetrikaReject;
use App\Models\GudangSetrikaRejectDetail;
use App\Models\GudangSetrikaMasuk;
use App\Models\GudangSetrikaMasukDetail;
use App\Models\GudangSetrikaRekap;
use App\Models\GudangSetrikaRekapDetail;
use App\Models\GudangSetrikaStokOpname;
use App\Models\Pegawai;

use DB;

class GudangSetrikaSetrikaler extends Setrikaler
{
    public function index()
    {
        $bajus = GudangSetrikaStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangSetrikaStokOpname::where('statusSetrika', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        return view('gudangSetrika.index', ['dataStok' => $dataStok]);
    }

    public function getData(Request $request)
    {
        $data = [];
        $i = 0;
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->where('jenisBaju', $request->jenisBaju)->groupBy('purchaseId')->get();

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
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 0)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
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
                                                            ->where('statusSetrika', 0)                                                             
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
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
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
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            $gdBatilReject = GudangJahitReject::where('gudangRequest', 'Gudang Setrika')->whereDate('tanggal', date('Y-m-d'))->first();
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
        $controlMasuk = GudangSetrikaMasuk::all();

        return view('gudangSetrika.request.index', ['gdSetrikaMasuk' => $controlMasuk]);
    }

    public function gRequestTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  
        $gudangSetrika = GudangSetrikaMasuk::where('id',$id)->first();
        $gudangSetrikaDetail = GudangSetrikaMasukDetail::where('gdSetrikaMId', $gudangSetrika->id)->get();
        
        $gudangSetrikaTerima = GudangSetrikaMasuk::updateStatusDiterima($id, $statusDiterima);

        if ($gudangSetrikaTerima == 1) {
            foreach ($gudangSetrikaDetail as $value) {
                $gdBajuStokOpname = GudangSetrikaStokOpname::BatilStokOpnameCreate($value->gdBajuStokOpnameId, null, $value->purchaseId, $value->jenisBaju, $value->ukuranBaju, 0, \Auth::user()->id);
            }
            if ($gdBajuStokOpname == 1) {
                return redirect('GSetrika/request');
            }
        }
    }

    public function gRequestDetail($id)
    {
        $SetrikaMasuk = GudangSetrikaMasuk::where('id', $id)->first();
        $SetrikaMasukDetail = GudangSetrikaMasukDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdSetrikaMId', $SetrikaMasuk->id)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangSetrika.request.detail', ['gdSetrikaMasukDetail' => $SetrikaMasukDetail]);
    }

    public function gOperator()
    {
        $dataPemindahan = [];
        $i = 0;        
        $gdRequestOperator = GudangSetrikaStokOpname::groupBy('jenisBaju', 'ukuranBaju')->whereDate('tanggal', date('Y-m-d'))->get();
        $gdSetrika = GudangSetrikaStokOpname::where('statusSetrika', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        $gdJahitRekap = GudangSetrikaRekap::orderBy('tanggal', 'DESC')->groupBy('tanggal')->get();
        $pindahan = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusSetrika', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($pindahan as $detail) {
            $jumlahBaju = 0;
            $checkBatilDetail = GudangSetrikaMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
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
        // $gdSetrikaMasuk = GudangSetrikaMasuk::where('tanggal', date('Y-m-d'))->first();
        // if ($gdSetrikaMasuk != null) {
        //     $gdSetrikaMasukDetail = GudangSetrikaMasukDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdSetrikaMId', $gdSetrikaMasuk->id)->groupBy('gdSetrikaMId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        // }else {
        //     $gdSetrikaMasukDetail = null;
        // }

        return view('gudangSetrika.operator.index', ['operatorRequest' => $gdRequestOperator, 'gdSetrika' => $gdSetrika, 'batilRekap' => $gdJahitRekap, 'dataPemindahan' => $pindahan, 'gdSetrikaMasuk' => $gdSetrikaMasuk]);
    }

    public function gOperatorDataMaterial($purchaseId, $jenisBaju, $ukuranBaju, $jumlahBaju)
    {
        $data = [];
        if ($ukuranBaju == "null") {
            $gdRequestOperator = GudangSetrikaStokOpname::select('ukuranBaju')->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->groupBy('ukuranBaju')->get();
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->ukuranBaju, $data)) {
                    $data[] = $operator->ukuranBaju;
                }
            }
        } else {
            $gdRequestOperator = GudangSetrikaStokOpname::where('tanggal', null)->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('statusSetrika', 0)->get();
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
        $gdRequestOperator = GudangSetrikaStokOpname::where('tanggal', $date)->get();

        return view('gudangSetrika.operator.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorCreate()
    {
        $gdRequestOperator = GudangSetrikaStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();

        return view('gudangSetrika.operator.create', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GSetrika/operator');
            }
        }else{
            return redirect('GSetrika/operator/create');
        }
    }

    public function gOperatorupdate($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        $gdSetrika = GudangSetrikaStokOpname::where('statusSetrika', 1)->whereDate('tanggal', date('Y-m-d'))->first();
        
        return view('gudangSetrika.operator.update', ['operatorRequest' => $gdRequestOperator, 'gdSetrika' => $gdSetrika, 'jenisBaju' => $jenisBaju]);
    }

    public function gOperatorupdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GSetrika/operator');
            }
        }else{
            return redirect('GSetrika/operator');
        }
    }

    public function gOperatorUpdateDelete($purchaseId, $jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangSetrikaStokOpname::where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GSetrika/operator/update/' . $jenisBaju . '/'. $ukuranBaju);
        }
    }

    public function gOperatorDelete(Request $request)
    {
        $gdRequestOperator = GudangSetrikaStokOpname::where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GSetrika/operator');
        }
    }



    public function gRekapCreate()
    {
        $pegawai = Pegawai::where('kodeBagian', 'control')->get();
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangSetrika.rekap.create', ['pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangSetrikaRekap::where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $controlRekap = GudangSetrikaRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $controlRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $controlRekapDetail = GudangSetrikaRekapDetail::SetrikaRekapDetailCreate($controlRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($controlRekapDetail == 1) {
                return redirect('GSetrika/operator');
            }
        } else {
            return redirect('GSetrika/rekap/create');
        }
    }

    public function gRekapDetail($id)
    {
        $getPegawai = GudangSetrikaRekap::where('id', $id)->first();
        $getDetailPegawai = GudangSetrikaRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdSetrikaRekapId', $getPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangSetrika.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id)
    {
        $pegawai = Pegawai::where('kodeBagian', 'control')->get();
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        $getRekapanPegawai = GudangSetrikaRekap::where('id', $id)->first();
        $getDetailRekapanPegawai = GudangSetrikaRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdSetrikaRekapId', $getRekapanPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangSetrika.rekap.update', ['id' => $getRekapanPegawai->id, 'rekapanPegawai' => $getDetailRekapanPegawai, 'pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapUpdateSave(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangSetrikaRekap::where('id', $request->id)->first();
                if ($checkPegawai == null) {
                    $batilRekap = GudangSetrikaRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $batilRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $batilRekapDetail = GudangSetrikaRekapDetail::SetrikaRekapDetailCreate($batilRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GSetrika/operator');
            }
        } else {
            return redirect('GSetrika/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $rekapDetailId)
    {
        $getDetailPegawai = GudangSetrikaRekapDetail::where('id', $rekapDetailId)->first();

        $getPegawai = GudangSetrikaRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangSetrikaRekapDetail::where('gdSetrikaRekapId', $getPegawai->id)->get();

        $operatorReq = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $getDetailPegawai->gdBajuStokOpnameId)->first();
        if ($operatorReq != null) {
            $operatorReqUpdate = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 0, $operatorReq->id);
            if ($operatorReqUpdate == 1) {
                $deleteDetailPegawai = GudangSetrikaRekapDetail::where('id', $rekapDetailId)->delete();
                if (count($CheckPegawai) == 1) {
                    $deletePegawai = GudangSetrikaRekap::where('id', $rekapId)->delete();

                    if ($deletePegawai) {
                        return redirect('GSetrika/operator');
                    }
                }

                if ($deleteDetailPegawai) {
                    return redirect('GSetrika/rekap/update/' . $rekapId . '');
                }
                
            }                    
        } 
    }



    public function gKeluarCreate()
    {
        $dataPemindahan = [];
        $gdKeluarBatil = GudangSetrikaStokOpname::where('statusSetrika', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdKeluarBatil as $detail) {
            if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) { 
                $dataPemindahan[] = [
                    'tanggal' => date('d F Y', strtotime($detail->tanggal)),
                    'gdBajuStokOpnameId' => $detail->gdBajuStokOpnameId,
                    'purchaseId' => $detail->purchaseId,
                    'purchase' => $detail->purchase->kode,
                    'jenisBaju' => $detail->jenisBaju,
                    'ukuranBaju' => $detail->ukuranBaju,
                    'statusSetrika' => $detail->statusSetrika,
                ];
            }            
        }

        for ($i=0; $i < count($dataPemindahan); $i++) { 
            $gdRekapDetail = GudangSetrikaRekapDetail::where('gdBajuStokOpnameId', $dataPemindahan[$i]['gdBajuStokOpnameId'])->get();
            foreach ($gdRekapDetail as $detail) {
                $dataPemindahan[$i]['controlName'] = $detail->pegawai->nama;                
            }
        }

        return view('gudangSetrika.keluar.create', ['gdKeluarSetrika' => $dataPemindahan]);
    }

    public function gKeluarStore(Request $request)
    {
        if (count($request->gdBajuStokOpnameId) != 0) {
            $checkBatilMasuk = GudangSetrikaMasuk::where('tanggal', date('Y-m-d'))->first();
            if ($checkBatilMasuk != null) {
                $batilMasuk = $checkBatilMasuk->id;
            }else {
                $batilMasuk = GudangSetrikaMasuk::createSetrikaMasuk();
            }

            for ($i=0; $i < count($request->gdBajuStokOpnameId); $i++) { 
                $batilMasukDetail = GudangSetrikaMasukDetail::createGudangSetrikaMasukDetail($batilMasuk, $request['gdBajuStokOpnameId'][$i], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
            }

            if ($batilMasukDetail == 1) {
                return redirect('GSetrika/operator');
            }
        } else {
            return redirect('GSetrika/operator');
        }
    }

    public function gKeluarDetail($date)
    {
        $dataPemindahan = [];
        $gdKeluarBatil = GudangSetrikaStokOpname::where('tanggal', $date)->get();
        foreach ($gdKeluarBatil as $detail) {
            if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) { 
                $dataPemindahan[] = [
                    'tanggal' => date('d F Y', strtotime($detail->tanggal)),
                    'gdBajuStokOpnameId' => $detail->gdBajuStokOpnameId,
                    'purchase' => $detail->purchase->kode,
                    'jenisBaju' => $detail->jenisBaju,
                    'ukuranBaju' => $detail->ukuranBaju,
                    'statusSetrika' => $detail->statusSetrika,
                ];
            }            
        }

        for ($i=0; $i < count($dataPemindahan); $i++) { 
            $gdRekapDetail = GudangSetrikaRekapDetail::where('gdBajuStokOpnameId', $dataPemindahan[$i]['gdBajuStokOpnameId'])->get();
            foreach ($gdRekapDetail as $detail) {
                $dataPemindahan[$i]['batilName'] = $detail->pegawai->nama;                
            }
        }

        return view('gudangSetrika.keluar.detail', ['gdKeluarBatil' => $dataPemindahan]);

    }



    public function gReject()
    {
        $gdSetrikaReject  = GudangSetrikaReject::all();
        $gdJahitReject = GudangJahitReject::where('gudangRequest', 'Gudang Setrika')->get();

        return view('gudangSetrika.reject.index', ['gdSetrikaReject' => $gdSetrikaReject, 'jahitReject' => $gdJahitReject]);
    }

    public function gRejectTJCreate()
    {
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangSetrika.reject.create', ['purchases' => $purchaseId]);

    }

    public function gRejectTJStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangJahitReject::where('gudangRequest', 'Gudang Setrika')->where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $batilReject = GudangJahitReject::CreateGudangJahitReject('Gudang Setrika', date('Y-m-d'), $request['jumlahBaju'][$i], \Auth::user()->id);
                } else {
                    $batilReject = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $batilRekapDetail = GudangJahitRejectDetail::createGudangJahitRejectDetail($batilReject, $operatorReqId[$j], $request['keterangan'][$i]);
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GSetrika/reject');
            }
        } else {
            return redirect('GSetrika/reject/create');
        }
    }

    public function gRejectTJDetail($id, $request)
    {
        if ($request == "Jahit") {
            $gdJahitReject = GudangJahitReject::where('id', $id)->first();
            $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdJahitReject->id)->get();
        }elseif ($request == "Setrika") {
            $gdJahitReject = GudangSetrikaReject::where('id', $id)->first();
            $gdJahitRejectDetail = GudangSetrikaRejectDetail::where('gdSetrikaRejectId', $gdJahitReject->id)->get();
        }

        return view('gudangSetrika.reject.detail', ['jahitRejectDetail' => $gdJahitRejectDetail]);
    }

    public function gRejectTJUpdate($id)
    {
        $purchaseId = [];
        $i = 0;
        $checkPurchaseId = GudangSetrikaStokOpname::whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();
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
            $checkBaju = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            $detail->kodePurchase = $checkBaju->purchase->kode;
            $detail->jenisBaju = $checkBaju->jenisBaju;
            $detail->ukuranBaju = $checkBaju->ukuranBaju;
            if ($detail->keterangan == null) {
                $detail->keterangan = " - ";
            }
        }
        return view('gudangSetrika.reject.update', ['purchases' => $purchaseId, 'gdJahitReject' => $gdJahitReject, 'jahitRejectDetail' => $gdJahitRejectDetail]);
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
                return redirect('GSetrika/reject');
            }
        } else {
            return redirect('GSetrika/reject/create');
        }
    }

    public function gRejectTJUpdateDelete($rejectId, $detailRejectId)
    {
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('id', $detailRejectId)->delete();
        if ($gdJahitRejectDetail) {
            return redirect('GSetrika/reject/update/'.$rejectId);
        }else{
            return redirect('GSetrika/reject/update/' . $rejectId . '');
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
                return redirect('GSetrika/reject');
            }
        }else{
            GudangJahitReject::where('id', $request->rejectId)->delete();
            return redirect('GSetrika/reject');
        }

    }
}
