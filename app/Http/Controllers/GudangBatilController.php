<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBatilStokOpname;
use App\Models\GudangBatilMasuk;
use App\Models\GudangBatilMasukDetail;
use App\Models\GudangBatilRekap;
use App\Models\GudangBatilRekapDetail;

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

    public function gReject()
    {
        return view('gudangBatil.reject.index');
    }
}
