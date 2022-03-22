<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangControlStokOpname;
use App\Models\GudangControlMasuk;
use App\Models\GudangControlMasukDetail;
use App\Models\GudangControlRekap;
use App\Models\GudangControlRekapDetail;
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

        return view('gudangControl.operator.index', ['operatorRequest' => $gdRequestOperator, 'gdControl' => $gdControl, 'batilRekap' => $gdJahitRekap, 'dataPemindahan' => $pindahan, 'dataPemindahan' => $dataPemindahan, 'gdControlMasuk' => $gdSetrikaMasuk]);
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
}
