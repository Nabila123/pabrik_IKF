<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBatilStokOpname;
use App\Models\GudangBatilMasuk;
use App\Models\GudangBatilMasukDetail;
use App\Models\GudangBatilRekap;
use App\Models\GudangBatilRekapDetail;
use App\Models\Pegawai;

use DB;


class GudangBatilController extends Controller
{
    public function index()
    {
        $bajus = GudangBatilStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangBatilStokOpname::where('statusBatil', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        return view('gudangBatil.index', ['dataStok' => $dataStok]);
    }

    public function getPegawai(Request $request)
    {
        // dd($request);
        $data = [];

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangBatilStokOpname::where('statusBatil', 0)->where('purchaseId', $request->purchaseId)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangBatilStokOpname::where('statusBatil', 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('tanggal', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            $reqOperatorId = []; 

            $checkId = [];   
            $index = 0;    
            $checkId[$index]['operatorReqId'] = [];
            $checkId[$index]['purchase'] = [];
            $checkId[$index]['jumlah'] = [];

            $gdRequestOperator = GudangBatilStokOpname::where('purchaseId', $request->purchaseId)
                                                            ->where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)
                                                            ->where('statusBatil', 0)                                                             
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

    public function getData(Request $request)
    {
        $data = [];
        $i = 0;
        $purchaseId = GudangBatilStokOpname::select('purchaseId')->where('jenisBaju', $request->jenisBaju)->groupBy('purchaseId')->get();

        foreach ($purchaseId as $detail) {
            if (!in_array($detail->purchaseId, $data)) {
                $data[$i]['purchaseId'] = $detail->purchaseId;
                $data[$i]['kode'] = $detail->purchase->kode;
                $i++;
            }
        }
        return json_encode($data);
    }

    public function gRequest()
    {
        $batilMasuk = GudangBatilMasuk::all();

        return view('gudangBatil.request.index', ['gdBatilMasuk' => $batilMasuk]);
    }

    public function gRequestTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  
        $gudangBatil = GudangBatilMasuk::where('id',$id)->first();
        $gudangBatilDetail = GudangBatilMasukDetail::where('gdBatilMId', $gudangBatil->id)->get();
        
        $gudangBatilTerima = GudangBatilMasuk::updateStatusDiterima($id, $statusDiterima);

        if ($gudangBatilTerima == 1) {
            foreach ($gudangBatilDetail as $value) {
                $gdBajuStokOpname = GudangBatilStokOpname::BatilStokOpnameCreate($value->gdBajuStokOpnameId, null, $value->purchaseId, $value->jenisBaju, $value->ukuranBaju, 0, \Auth::user()->id);
            }
            if ($gdBajuStokOpname == 1) {
                return redirect('GBatil/request');
            }
        }
    }

    public function gRequestDetail($id)
    {
        $batilMasuk = GudangBatilMasuk::where('id', $id)->first();
        $batilMasukDetail = GudangBatilMasukDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdBatilMId', $batilMasuk->id)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangBatil.request.detail', ['gdBatilMasukDetail' => $batilMasukDetail]);
    }

    public function gOperator()
    {
        // $dataPemindahan = [];
        // $i = 0;        
        $gdRequestOperator = GudangBatilStokOpname::groupBy('jenisBaju', 'ukuranBaju')->whereDate('tanggal', date('Y-m-d'))->get();
        $gdBatil = GudangBatilStokOpname::where('statusBatil', 1)->whereDate('tanggal', date('Y-m-d'))->get();
        $gdJahitRekap = GudangBatilRekap::orderBy('tanggal', 'DESC')->groupBy('tanggal')->get();
        $pindahan = GudangBatilStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('statusBatil', 1)->whereDate('created_at', date('Y-m-d'))->get();
        // foreach ($pindahan as $detail) {
        //     $jumlahBaju = 0;
        //     $checkBatilDetail = GudangBatilMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
        //     if ($checkBatilDetail == null) {
        //         $target = [$detail->jenisBaju, $detail->ukuranBaju];                
        //         if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) {  
        //             if ($dataPemindahan != null && count(array_intersect($dataPemindahan[$i-1], $target)) == count($target)) {
        //                 $jumlahBaju = 1;
        //             }        

        //             if($jumlahBaju != 0){
        //                 $dataPemindahan[$i-1]['jumlahBaju'] += $jumlahBaju;
        //             }else{
        //                 $dataPemindahan[$i] = [
        //                     'tanggal' => date('d F Y', strtotime($detail->created_at)),
        //                     'jenisBaju' => $detail->jenisBaju,
        //                     'ukuranBaju' => $detail->ukuranBaju,
        //                     'jumlahBaju' => 1,
        //                 ];
        //                 $i++;
        //             }
        //         }
        //     }
        // }
        
        return view('gudangBatil.operator.index', ['operatorRequest' => $gdRequestOperator, 'gdBatil' => $gdBatil, 'batilRekap' => $gdJahitRekap, 'dataPemindahan' => $pindahan]);
    }

    public function gOperatorDetail($date)
    {
        $gdRequestOperator = GudangBatilStokOpname::where('tanggal', $date)->get();

        return view('gudangBatil.operator.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorCreate()
    {
        $gdRequestOperator = GudangBatilStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();

        return view('gudangBatil.operator.create', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorStore(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangBatilStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangBatilStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GBatil/operator');
            }
        }else{
            return redirect('GBatil/operator/create');
        }
    }

    public function gOperatorDataMaterial($purchaseId, $jenisBaju, $ukuranBaju, $jumlahBaju)
    {
        $data = [];
        if ($ukuranBaju == "null") {
            $gdRequestOperator = GudangBatilStokOpname::select('ukuranBaju')->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->groupBy('ukuranBaju')->get();
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->ukuranBaju, $data)) {
                    $data[] = $operator->ukuranBaju;
                }
            }
        } else {
            $gdRequestOperator = GudangBatilStokOpname::where('tanggal', null)->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('statusBatil', 0)->get();
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

    public function gOperatorupdate($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangBatilStokOpname::select('*', DB::raw('count(*) as jumlah'))->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('tanggal', date('Y-m-d'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        $gdBatil = GudangBatilStokOpname::where('statusBatil', 1)->whereDate('tanggal', date('Y-m-d'))->first();
        
        return view('gudangBatil.operator.update', ['operatorRequest' => $gdRequestOperator, 'gdBatil' => $gdBatil, 'jenisBaju' => $jenisBaju]);
    }

    public function gOperatorupdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangBatilStokOpname::where('id', $operatorReqId[$j])->first();

                    $batilStokOpname = GudangBatilStokOpname::bajuUpdateField('tanggal', date('Y-m-d'), $operatorReq->id);
                }
            }

            if ($batilStokOpname == 1) {
                return redirect('GBatil/operator');
            }
        }else{
            return redirect('GBatil/operator');
        }
    }

    public function gOperatorUpdateDelete($purchaseId, $jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangBatilStokOpname::where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangBatilStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GBatil/operator/update/' . $jenisBaju . '/'. $ukuranBaju);
        }
    }

    public function gOperatorDelete(Request $request)
    {
        $gdRequestOperator = GudangBatilStokOpname::where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->whereDate('tanggal', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $detail) {
            $batilStokOpname = GudangBatilStokOpname::bajuUpdateField('tanggal', null, $detail->id);
        }
        if ($batilStokOpname == 1) {
            return redirect('GBatil/operator');
        }
    }   

    public function gRekapCreate()
    {
        $pegawai = Pegawai::where('kodeBagian', 'batil')->get();
        $purchaseId = GudangBatilStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        return view('gudangBatil.rekap.create', ['pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangBatilRekap::where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $batilRekap = GudangBatilRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $batilRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangBatilStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangBatilStokOpname::bajuUpdateField('statusBatil', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $batilRekapDetail = GudangBatilRekapDetail::BatilRekapDetailCreate($batilRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GBatil/operator');
            }
        } else {
            return redirect('GBatil/rekap/create');
        }
    }

    public function gRekapDetail($id)
    {
        $getPegawai = GudangBatilRekap::where('id', $id)->first();
        $getDetailPegawai = GudangBatilRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdBatilRekapId', $getPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangBatil.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id)
    {
        $pegawai = Pegawai::where('kodeBagian', 'batil')->get();
        $purchaseId = GudangBatilStokOpname::select('purchaseId')->whereDate('tanggal', date('Y-m-d'))->groupBy('purchaseId')->get();

        $getRekapanPegawai = GudangBatilRekap::where('id', $id)->first();
        $getDetailRekapanPegawai = GudangBatilRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdBatilRekapId', $getRekapanPegawai->id)->groupBy('pegawaiId', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();

        return view('gudangBatil.rekap.update', ['id' => $getRekapanPegawai->id, 'rekapanPegawai' => $getDetailRekapanPegawai, 'pegawais' => $pegawai, 'purchases' => $purchaseId]);
    }

    public function gRekapUpdateSave(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangBatilRekap::where('id', $request->id)->first();
                if ($checkPegawai == null) {
                    $batilRekap = GudangBatilRekap::BatilRekapCreate(\Auth::user()->id);
                } else {
                    $batilRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangBatilStokOpname::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $bajuStokOpname = GudangBatilStokOpname::bajuUpdateField('statusBatil', 1, $operatorReq->id);
                        if ($bajuStokOpname == 1) {
                            $batilRekapDetail = GudangBatilRekapDetail::BatilRekapDetailCreate($batilRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                        }              
                    } 
                }                                             
            }

            if ($batilRekapDetail == 1) {
                return redirect('GBatil/operator');
            }
        } else {
            return redirect('GBatil/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $rekapDetailId)
    {
        $getDetailPegawai = GudangBatilRekapDetail::where('id', $rekapDetailId)->first();

        $getPegawai = GudangBatilRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangBatilRekapDetail::where('gdBatilRekapId', $getPegawai->id)->get();

        $operatorReq = GudangBatilStokOpname::where('gdBajuStokOpnameId', $getDetailPegawai->gdBajuStokOpnameId)->first();
        if ($operatorReq != null) {
            $operatorReqUpdate = GudangBatilStokOpname::bajuUpdateField('statusBatil', 0, $operatorReq->id);
            if ($operatorReqUpdate == 1) {
                $deleteDetailPegawai = GudangBatilRekapDetail::where('id', $rekapDetailId)->delete();
                if (count($CheckPegawai) == 1) {
                    $deletePegawai = GudangBatilRekap::where('id', $rekapId)->delete();

                    if ($deletePegawai) {
                        return redirect('GBatil/operator');
                    }
                }

                if ($deleteDetailPegawai) {
                    return redirect('GBatil/rekap/update/' . $rekapId . '');
                }
                
            }                    
        } 
    }

    public function gReject()
    {
        return view('gudangBatil.reject.index');
    }
}