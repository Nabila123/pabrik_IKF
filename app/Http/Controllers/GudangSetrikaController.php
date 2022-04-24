<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\GudangSetrikaStokOpname;
use App\Models\GudangSetrikaMasuk;
use App\Models\GudangSetrikaMasukDetail;
use App\Models\GudangSetrikaRekap;
use App\Models\GudangSetrikaRekapDetail;
use App\Models\GudangControlReject;
use App\Models\GudangControlRejectDetail;
use App\Models\Pegawai;

use DB;

class GudangSetrikaController extends Controller
{
    public function index()
    {
        $bajus = GudangSetrikaStokOpname::select('jenisBaju', 'ukuranBaju')->groupBy('jenisBaju', 'ukuranBaju')->get();
        $data = GudangSetrikaStokOpname::where('statusSetrika', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['ukuran'] = $baju->ukuranBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] = $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] + 1;
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
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 0)->where('statusReject', 0)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('statusSetrika', 0)->where('statusReject', 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
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
                                                            ->where('statusReject', 0)                                                             
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            if (isset($request->operatorReqPurchaseId)) {
                for ($i=0; $i < count($request['operatorReqPurchaseId']); $i++) { 
                    if ($request['operatorReqPurchaseId'][$i] == null) {
                        continue;
                    }                    
                    
                    if ($index != 0) {
                        $getCheck = false;
                        for ($j=0; $j < count($checkId); $j++) { 
                            if($request['operatorReqPurchaseId'][$i] == $checkId[$j]['purchase']){
                                $getCheck = true;
                                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                                for ($k=0; $k < count($operatorReqId); $k++) { 
                                    if (!in_array($operatorReqId[$k], $checkId[$j]['operatorReqId'])) {
                                        $checkId[$j]['operatorReqId'][] = $operatorReqId[$k];
                                        $checkId[$j]['jumlah'] += 1;
                                    }            
                                }
                            }
                        }
                        if ($getCheck == false) {
                            $checkId[$index]['purchase'] = $request['operatorReqPurchaseId'][$i];
                            $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                            for ($j=0; $j < count($operatorReqId); $j++) { 
                                $checkId[$index]['operatorReqId'][] = $operatorReqId[$j];
                            }
                            $checkId[$index]['jumlah'] = count($operatorReqId);
                            $index++;
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
            }else{
                $request->jumlahBaju = $request->jumlahBaju*12;
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
            $gdRequestOperator = GudangSetrikaStokOpname::where('purchaseId', $request->purchaseId)->where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangSetrikaStokOpname::where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
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
                                                            ->where('statusPacking', null)
                                                            ->whereDate('tanggal', date('Y-m-d'))->get();

            $gdSetrikaReject = GudangControlReject::where('gudangRequest', 'Gudang Setrika')->whereDate('tanggal', date('Y-m-d'))->first();
            if ($gdSetrikaReject != null) {
                $gdSetrikaRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdSetrikaReject->id)->get();
                if ($gdSetrikaRejectDetail != null) {
                    foreach ($gdSetrikaRejectDetail as $detail) {
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
        $setrikaMasuk = GudangSetrikaMasuk::all();

        return view('gudangSetrika.request.index', ['gdSetrikaMasuk' => $setrikaMasuk]);
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
                $gdBajuStokOpname = GudangSetrikaStokOpname::SetrikaStokOpnameCreate($value->gdBajuStokOpnameId, null, $value->purchaseId, $value->jenisBaju, $value->ukuranBaju, 0, \Auth::user()->id);
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
        
        $gdRequestOperator = GudangSetrikaStokOpname::select("*", DB::raw('count(*) as jumlah'))->groupBy('jenisBaju', 'ukuranBaju')->where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $reqOperator) {
            $reqOperator->totalDz =  (int)($reqOperator->jumlah/12);
            $sisa = ($reqOperator->jumlah%12);
            if ($sisa != 0) {
                $reqOperator->sisa = $sisa;
            }  
            // dd($sisa);
        }
        // dd($gdRequestOperator);
        $gdSetrika = GudangSetrikaStokOpname::where('statusSetrika', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        $gdSetrikaRekap = GudangSetrikaRekap::orderBy('tanggal', 'DESC')->groupBy('tanggal')->get();
        $pindahan = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusSetrika', 1)->where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
   
        $gdPackingMasuk = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusSetrika', 1)->where('statusPacking', 0)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        
        return view('gudangSetrika.operator.index', ['operatorRequest' => $gdRequestOperator, 'gdSetrika' => $gdSetrika, 'setrikaRekap' => $gdSetrikaRekap, 'dataPemindahan' => $pindahan, 'gdPackingMasuk' => $gdPackingMasuk]);
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

    public function gOperatorDetail($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangSetrikaStokOpname::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('tanggal', date('Y-m-d'))->get();

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

                    $setrikaStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($setrikaStokOpname == 1) {
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

                    $setrikaStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($setrikaStokOpname == 1) {
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
            $setrikaStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($setrikaStokOpname == 1) {
            return redirect('GSetrika/operator/update/' . $jenisBaju . '/'. $ukuranBaju);
        }
    }

    public function gOperatorDelete(Request $request)
    {
        $gdRequestOperator = GudangSetrikaStokOpname::where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $setrikaStokOpname = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($setrikaStokOpname == 1) {
            return redirect('GSetrika/operator');
        }
    }



    public function gRekapCreate()
    {
        $pegawai = Pegawai::where('kodeBagian', 'setrika')->get();
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->where('statusSetrika', 0)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangSetrika.rekap.create', ['pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangSetrikaRekap::where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $setrikaRekap = GudangSetrikaRekap::setrikaRekapCreate(\Auth::user()->id);
                } else {
                    $setrikaRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $setrikaRekapDetail = GudangSetrikaRekapDetail::SetrikaRekapDetailCreate($setrikaRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($setrikaRekapDetail == 1) {
                return redirect('GSetrika/operator');
            }
        } else {
            return redirect('GSetrika/rekap/create');
        }
    }

    public function gRekapDetail($id)
    {
        $totalControlPegawai = [];
        $total = 0;
        $getPegawai = GudangSetrikaRekap::where('id', $id)->first();
        $getDetailPegawai = GudangSetrikaRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdSetrikaRekapId', $getPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        foreach ($getDetailPegawai as $detailPegawai) {
            $getCountPegawai = GudangSetrikaRekapDetail::select(DB::raw('count(*) as jumlah'))->where('pegawaiId', $detailPegawai->pegawaiId)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->whereDate('created_at', date('Y-m-d'))->get();
            if (!in_array($detailPegawai->pegawaiId, $totalControlPegawai)) {
                $totalControlPegawai[] = $detailPegawai->pegawaiId;
                foreach ($getCountPegawai as $countPegawai) {
                    $total += $countPegawai->jumlah;
                }
                $detailPegawai->jumlahTotal = $total/12;
                $detailPegawai->rowSpan = count($getCountPegawai);
                $total = 0;
            }
            $detailPegawai->posisi = $getPegawai->posisi;        
        }

        return view('gudangSetrika.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id)
    {
        $pegawai = Pegawai::where('kodeBagian', 'Setrika')->get();
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
                    $SetrikalRekap = GudangSetrikaRekap::SetrikalRekapCreate(\Auth::user()->id);
                } else {
                    $SetrikalRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangSetrikaStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $SetrikalRekapDetail = GudangSetrikaRekapDetail::SetrikaRekapDetailCreate($SetrikalRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($SetrikalRekapDetail == 1) {
                return redirect('GSetrika/operator');
            }
        } else {
            return redirect('GSetrika/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $pegawaiId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $getPegawai = GudangSetrikaRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangSetrikaRekapDetail::where('gdSetrikaRekapId', $getPegawai->id)->get();
        $getDetailPegawai = GudangSetrikaRekapDetail::where('gdSetrikaRekapId', $getPegawai->id)
                                                    ->where('pegawaiId', $pegawaiId)
                                                    ->where('purchaseId', $purchaseId)
                                                    ->where('jenisBaju', $jenisBaju)
                                                    ->where('ukuranBaju', $ukuranBaju)
                                                    ->get();

        foreach ($getDetailPegawai as $detailPegawai) {
            $operatorReq = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detailPegawai->gdBajuStokOpnameId)->first();
            if ($operatorReq != null) {
                $operatorReqUpdate = GudangSetrikaStokOpname::bajuUpdateField('statusSetrika', 0, $operatorReq->id);
            }
        }

        if ($operatorReqUpdate == 1) {
            $deleteDetailPegawai = GudangSetrikaRekapDetail::where('gdSetrikaRekapId', $getPegawai->id)
                                                            ->where('pegawaiId', $pegawaiId)
                                                            ->where('purchaseId', $purchaseId)
                                                            ->where('jenisBaju', $jenisBaju)
                                                            ->where('ukuranBaju', $ukuranBaju)
                                                            ->delete();
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

    public function gKeluarCreate($jenisBaju, $ukuranBaju, $date)
    {
        $dataPemindahan = [];
        $gdKeluarSetrika = GudangSetrikaStokOpname::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('statusSetrika', 1)->where('statusPacking', null)->where('statusReject', 0)->whereDate('tanggal', date('Y-m-d'))->get();
        $getBajuDetail = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('statusSetrika', 1)->where('statusPacking', null)->where('statusReject', 0)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->first();
        foreach ($gdKeluarSetrika as $detail) {
            $gdControlMasukDetail = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->where('statusPacking', 1)->where('statusReject', 0)->first();
            if ($gdControlMasukDetail != null) {
                $getBajuDetail->jumlah -= 1;
                $getBajuDetail->ambilPcs = $getBajuDetail->jumlah;
            }else {
                $dataPemindahan[] = $detail->gdBajuStokOpnameId;
                $getBajuDetail->ambilPcs = $getBajuDetail->jumlah/12;
            }        
        }

        $getBajuDetail->dataPemindahan = preg_replace("/[^0-9]/", ",", json_encode($dataPemindahan));

        return view('gudangSetrika.keluar.create', ['gdKeluarSetrika' => $getBajuDetail]);
    }

    public function gKeluarStore(Request $request)
    {
        // dd($request);
        if (count($request->gdBajuStokOpnameId) != 0 && count($request->totalDz) != 0) { 
            for ($i=0; $i < count($request->gdBajuStokOpnameId); $i++) { 
                $gdBajuStokOpnameId = explode(",", $request['gdBajuStokOpnameId'][$i]);
                for ($j=1; $j < count($gdBajuStokOpnameId)-1; $j++) {
                    if ($j <= ($request['totalDz'][$i]*12)) {
                        $updateStatusPacking = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $gdBajuStokOpnameId[$j])->update(['statusPacking'=>0]);
                    }
                }
            }
        }
        return redirect('GSetrika/operator');
    }

    public function gKeluarDetail($jenisBaju, $ukuranBaju, $date)
    {
        $dataPemindahan = [];
        $gdKeluarSetrikal = GudangSetrikaStokOpname::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('tanggal', $date)->get();
        foreach ($gdKeluarSetrikal as $detail) {
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
                $dataPemindahan[$i]['SetrikaName'] = $detail->pegawai->nama;                
            }
        }

        return view('gudangSetrika.keluar.detail', ['gdKeluarSetrika' => $dataPemindahan]);

    }



    public function gReject()
    {
        $gdControlReject = GudangControlReject::where('gudangRequest', 'Gudang Setrika')->get();

        return view('gudangSetrika.reject.index', ['controlReject' => $gdControlReject]);
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
        $purchaseId = GudangSetrikaStokOpname::select('purchaseId')->where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangSetrika.reject.create', ['purchases' => $purchaseId]);

    }

    public function gRejectStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlReject::where('gudangRequest', 'Gudang Setrika')->where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $SetrikalReject = GudangControlReject::CreateGudangControlReject('Gudang Setrika', date('Y-m-d'), $request['jumlahBaju'][$i], \Auth::user()->id);
                } else {
                    $setrikaReject = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $getBatil = GudangSetrikaStokOpname::where('gdBajuStokOpnameId',$operatorReqId[$j])->first();
                    $tanggalBatil = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $getBatil->id);
                    if ($tanggalBatil) {
                        $statusReject = GudangSetrikaStokOpname::bajuUpdateField('statusReject', 1, $getBatil->id);
                        if ($statusReject) {
                            $setrikaRekapDetail = GudangControlRejectDetail::createGudangControlRejectDetail($SetrikalReject, $operatorReqId[$j], $request['keterangan'][$i]);
                        }                                             
                    }                                             
                }                                             
            }

            if ($setrikaRekapDetail == 1) {
                return redirect('GSetrika/reject');
            }
        } else {
            return redirect('GSetrika/reject/create');
        }
    }

    public function gRejectDetail($id)
    {
        $gdControlReject = GudangControlReject::where('id', $id)->first();
        $gdControlRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdControlReject->id)->get();
    
        return view('gudangSetrika.reject.detail', ['controlRejectDetail' => $gdControlRejectDetail]);
    }

    public function gRejectUpdate($id)
    {
        $purchaseId = [];
        $i = 0;
        $checkPurchaseId = GudangSetrikaStokOpname::where('statusPacking', null)->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();
        foreach ($checkPurchaseId as $purchase) {
            $gdControlRejectDetail = GudangControlRejectDetail::where('gdBajuStokOpnameId', $purchase->id)->whereDate('created_at', date('Y-m-d'))->first();
            if ($gdControlRejectDetail == null) {
                if (!in_array($purchase->purchaseId, $purchaseId)) {
                    $purchaseId[$i]['purchaseId'] = $purchase->purchaseId;
                    $purchaseId[$i]['kodePurchase'] = $purchase->purchase->kode;
                }
            }
        }
        $gdControlReject = GudangControlReject::where('id', $id)->first();
        $gdControlRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $gdControlReject->id)->get();
        foreach ($gdControlRejectDetail as $detail) {
            $checkBaju = GudangSetrikaStokOpname::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            $detail->kodePurchase = $checkBaju->purchase->kode;
            $detail->jenisBaju = $checkBaju->jenisBaju;
            $detail->ukuranBaju = $checkBaju->ukuranBaju;
            if ($detail->keterangan == null) {
                $detail->keterangan = " - ";
            }
        }
        return view('gudangSetrika.reject.update', ['purchases' => $purchaseId, 'gdControlReject' => $gdControlReject, 'controlRejectDetail' => $gdControlRejectDetail]);
    }

    public function gRejectUpdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangControlReject::where('id', $request->id)->first();
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                $total = $checkPegawai->totalBaju + count($operatorReqId);
                GudangControlReject::updateStatusDiterima('totalBaju', $total, $checkPegawai->id);
                
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $getBatil = GudangSetrikaStokOpname::where('gdBajuStokOpnameId',$operatorReqId[$j])->first();
                    $tanggalBatil = GudangSetrikaStokOpname::bajuUpdateField('tanggal', null, $getBatil->id);
                    if ($tanggalBatil) {
                        $statusReject = GudangSetrikaStokOpname::bajuUpdateField('statusReject', 1, $getBatil->id);
                        if ($statusReject) {
                            $setrikaRekapDetail = GudangControlRejectDetail::createGudangControlRejectDetail($checkPegawai->id, $operatorReqId[$j], $request['keterangan'][$i]);
                        }                                             
                    }               
                }                                             
            }

            if ($setrikaRekapDetail == 1) {
                return redirect('GSetrika/reject');
            }
        } else {
            return redirect('GSetrika/reject/create');
        }
    }

    public function gRejectUpdateDelete($rejectId, $detailRejectId)
    {
        $gdControlRejectDetail = GudangControlRejectDetail::where('id', $detailRejectId)->delete();
        if ($gdControlRejectDetail) {
            return redirect('GSetrika/reject/update/'.$rejectId);
        }else{
            return redirect('GSetrika/reject/update/' . $rejectId . '');
        }

    }

    public function gRejectDelete(Request $request)
    {
        // dd($request);
        $setrikaReject = GudangControlReject::where('id', $request->rejectId)->first();
        $setrikaRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $setrikaReject->id)->get();
        if (count($setrikaRejectDetail) != 0) {
            $setrikaRejectDetail = GudangControlRejectDetail::where('gdControlRejectId', $setrikaReject->id)->delete();
            if ($setrikaRejectDetail) {
                GudangControlReject::where('id', $request->rejectId)->delete();
                return redirect('GSetrika/reject');
            }
        }else{
            GudangControlReject::where('id', $request->rejectId)->delete();
            return redirect('GSetrika/reject');
        }

    }
}
