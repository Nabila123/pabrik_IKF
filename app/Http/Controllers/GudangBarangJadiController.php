<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBarangJadiStokOpname;
use App\Models\GudangPotongRequest;
use App\Models\GudangPotongRequestDetail;
use App\Models\GudangBarangJadiPenjualan;
use App\Models\GudangBarangJadiPenjualanDetail;
use DB;


class GudangBarangJadiController extends Controller
{
    public function index()
    {
        $bajus = GudangBarangJadiStokOpname::select('jenisBaju', DB::raw('count(*) as jumlah'))->groupBy('jenisBaju')->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = ($baju->jumlah / 6);
        }
        
        return view('gudangBarangJadi.index', ['dataStok' => $dataStok]);
    }

    public function getBarangJadi(Request $request)
    {
        $data = [];

        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangBarangJadiStokOpname::where('jenisBaju', $request->jenisBaju)->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            // dd($request);
            $reqOperatorId = []; 
            $checkId = [];   
            $checkId['kodeProduct'] = [];
            $checkId['jumlah'] = [];

            $gdRequestOperator = GudangBarangJadiStokOpname::where('jenisBaju', $request->jenisBaju)
                                                            ->where('ukuranBaju', $request->ukuranBaju)   
                                                            ->groupBy($request->groupBy)        
                                                            ->get();
            // dd($gdRequestOperator);
            if (isset($request->operatorReqId)) {
                for ($i=0; $i < count($request['operatorReqId']); $i++) { 
                    if ($request['operatorReqId'][$i] == null) {
                        continue;
                    }else {
                        $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                        for ($j=0; $j < count($operatorReqId); $j++) { 
                            $checkId['kodeProduct'][] = $operatorReqId[$j];
                            $checkId['jumlah'] = count($operatorReqId);
                        }
                    }                    
                }
            }

            // dd($checkId);
            
            if (!isset($request->jumlahBaju)) {
                $request->jumlahBaju = count($gdRequestOperator);
            } 
            
            $i = 0;
            foreach ($gdRequestOperator as $operator) {
                if (isset($request->operatorReqId)) {
                    $cek = false;
                    for ($j=0; $j < count($checkId); $j++) { 
                        if (!in_array($operator->kodeProduct, $checkId['kodeProduct'])) {
                            if (!in_array($operator->kodeProduct, $reqOperatorId)) {
                                if ($i < $request->jumlahBaju) {
                                    $reqOperatorId[$i] = $operator->kodeProduct;
                                    $i++;                                    
                                }                                   
                            }
                        }                                               
                    }
                } else {
                    if (!in_array($operator->kodeProduct, $reqOperatorId) && $i < $request->jumlahBaju) {
                        $reqOperatorId[$i] = $operator->kodeProduct;
                        $i++;
                    }
                }
            }            
        }     

        if (isset($gdRequestOperator)) {
            foreach ($gdRequestOperator as $operator) {
                if ($request->groupBy == "ukuranBaju") {
                    $data['operator'][] = [
                        'ukuranBaju' => $operator->ukuranBaju
                    ];
                }elseif ($request->groupBy == "kodeProduct") {
                    $data['operator'] = [
                        'requestOperatorId' => $reqOperatorId,
                        'jumlahBaju' => count($reqOperatorId)
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
    
    public function gOperator()
    {
        $penjualan = GudangBarangJadiPenjualan::where('tanggal', date('Y-m-d'))->get();
        foreach ($penjualan as $value) {
            if ($value->customer == null) {
                $value->customer = " - ";
            }
        }

        return view('gudangBarangJadi.operator.index', ['penjualans' => $penjualan]);

    }

    public function goperatorcreate()
    {
        $kodeTransaksi = GudangBarangJadiPenjualan::kodeTransaksi();
        $jenisBaju = GudangBarangJadiStokOpname::groupBy('jenisBaju')->get();

        return view('gudangBarangJadi.operator.create', ['kodeTransaksi' => $kodeTransaksi, 'jenisBaju' => $jenisBaju]);
    }

    public function goperatorstore(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {            
            $BarangJadiPenjualan = GudangBarangJadiPenjualan::CreateBarangJadiPenjualan($request['kodeTransaksi'], $request['customer'], date('Y-m-d'), $request['total']);
            
            for ($i=0; $i < $request->jumlah_data; $i++) {
                $kodeProduct = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($kodeProduct); $j++){
                    $barangJadi = GudangBarangJadiStokOpname::where('kodeProduct', $kodeProduct[$j])->first();

                    $BarangJadiPenjualanDetail = GudangBarangJadiPenjualanDetail::CreateBarangJadiPenjualanDetail($BarangJadiPenjualan, $barangJadi->kodeProduct, $barangJadi->purchaseId, $barangJadi->jenisBaju, $barangJadi->ukuranBaju, $request['jumlahBaju'][$i], $request['hargaBaju'][$i]);
                    if ($BarangJadiPenjualanDetail == 1) {
                        GudangBarangJadiStokOpname::where('kodeProduct', $kodeProduct[$j])->delete();
                    }
                }            
            }

            if ($BarangJadiPenjualanDetail == 1) {
                return redirect('GBarangJadi/operator');
            }
        }else{
            return redirect('GBarangJadi/operator/create');
        }
    }

    public function goperatorDetail($penjualanId)
    {
        $penjualan = GudangBarangJadiPenjualan::where('id', $penjualanId)->first();
        $penjualanDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $penjualan->id)->groupby('jenisBaju', 'ukuranBaju', 'qty')->get();

        if ($penjualan->customer == null) {
            $penjualan->customer = " - ";
        }

        return view('gudangBarangJadi.operator.detail', ['penjualan' => $penjualan, 'penjualanDetail' => $penjualanDetail]);
    }

    public function goperatorupdate($id)
    {
        $jenisBaju = GudangBarangJadiStokOpname::groupBy('jenisBaju')->get();
        $penjualan = GudangBarangJadiPenjualan::where('id', $id)->first();
        $penjualanDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $id)->groupby('jenisBaju', 'ukuranBaju', 'qty')->get();

        return view('gudangBarangJadi.operator.update', ['penjualan' => $penjualan, 'penjualanDetail' => $penjualanDetail, 'jenisBaju' => $jenisBaju]);
    }

    public function goperatorUpdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {            
            $BarangJadiPenjualan = GudangBarangJadiPenjualan::where('id', $request->penjualanId)->first();
            GudangBarangJadiPenjualan::PenjualanUpdateField('customer', $request['customer'], $BarangJadiPenjualan->implode);
            
            for ($i=0; $i < $request->jumlah_data; $i++) {
                $kodeProduct = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($kodeProduct); $j++){
                    $barangJadi = GudangBarangJadiStokOpname::where('kodeProduct', $kodeProduct[$j])->first();

                    $BarangJadiPenjualanDetail = GudangBarangJadiPenjualanDetail::CreateBarangJadiPenjualanDetail($BarangJadiPenjualan->id, $barangJadi->kodeProduct, $barangJadi->purchaseId, $barangJadi->jenisBaju, $barangJadi->ukuranBaju, $request['jumlahBaju'][$i], $request['hargaBaju'][$i]);
                }            
            }

            if ($BarangJadiPenjualanDetail == 1) {
                return redirect('GBarangJadi/operator');
            }
        }else{
            return redirect('GBarangJadi/operator/create');
        }
    }

    public function goperatorUpdateDelete($penjualanId, $penjualanDetailId, $jenisBaju, $ukuranBaju, $qty)
    {
        // dd($penjualanId);
        $RequestPotongDetail = GudangBarangJadiPenjualanDetail::where('id', $penjualanDetailId)
                                                                ->where('jenisBaju', $jenisBaju)
                                                                ->where('ukuranBaju', $ukuranBaju)
                                                                ->where('qty', $qty)
                                                                ->first();
        $tempJumlah = $RequestPotongDetail->qty;
        $tempHarga = $RequestPotongDetail->harga;
        $total = (int)$tempJumlah * (int)$tempHarga;

        $PotongDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $penjualanId)
                                                        ->where('jenisBaju', $jenisBaju)
                                                        ->where('ukuranBaju', $ukuranBaju)
                                                        ->where('qty', $qty)
                                                        ->delete();

        $penjualan = GudangBarangJadiPenjualan::where('id', $penjualanId)->first();
        $penjualanDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $penjualan->id)->get();

        $hasil = (int)$penjualan->totalHarga - $total;

        $updatePenjualan = GudangBarangJadiPenjualan::PenjualanUpdateField('totalHarga', $hasil, $penjualanId);

        if (count($penjualanDetail) == 0) {
            $RequestPotong = GudangBarangJadiPenjualan::where('id', $penjualanId)->delete();

            if ($RequestPotong == 1) {
                return redirect('GBarangJadi/operator');
            }

        }else{
            if ($PotongDetail == 1) {
                return redirect('/GBarangJadi/operator/update/' . $penjualanId);
            }
        }
    }

    public function goperatorDelete(Request $request)
    {
        $requestPotong = GudangBarangJadiPenjualan::where('id', $request->rejectId)->first();
        $requestPotongDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $requestPotong->id)->get();
        if (count($requestPotongDetail) != 0) {
            $requestPotongDetail = GudangBarangJadiPenjualanDetail::where('barangJadiPenjualanId', $requestPotong->id)->delete();
            if ($requestPotongDetail) {
                GudangBarangJadiPenjualan::where('id', $request->rejectId)->delete();
                return redirect('GBarangJadi/operator');
            }
        }else{
            GudangBarangJadiPenjualan::where('id', $request->rejectId)->delete();
            return redirect('GBarangJadi/operator');
        }
    }
    
    public function gRequestPotong()
    {
        $gdPotongReq = GudangPotongRequest::all();
        return view('gudangBarangJadi.requestPotong.index', ['gdPotongReq' => $gdPotongReq]);

    }

    public function gRequestPotongDetail($id)
    {
        $gdPotongRequest = GudangPotongRequest::where('id', $id)->first();
        $gdPotongRequestDetail = GudangPotongRequestDetail::where('gdPotongReqId', $gdPotongRequest->id)->get();
        
        if ($gdPotongRequest->statusDiterima == 0) {
            $gdPotongRequest->status = "Barang Belum Diproses";
            $gdPotongRequest->color = "#dc3545";
        }else{
            $gdPotongRequest->status = "Barang Sedang Diproses";
            $gdPotongRequest->color = "#28a745";
        }

        foreach ($gdPotongRequestDetail as $detail) {
            $dz = $detail->qty/12;
            $sisa = $detail->qty % 12;

            $detail->dz = (int)$dz." / ". $sisa;
        }

        return view('gudangBarangJadi.requestPotong.detail', ['gdPotongRequest' => $gdPotongRequest, 'gdPotongRequestDetail' => $gdPotongRequestDetail]);
    }

    public function gRequestPotongcreate()
    {
        return view('gudangBarangJadi.requestPotong.create');
    }

    public function gRequestPotongstore(Request $request)
    {
        if ($request->jumlah_data != 0) {
            $checkPotongRequest = GudangPotongRequest::where('tanggal', date('Y-m-d'))->first();
            if ($checkPotongRequest == null) {
                $potongRequest = GudangPotongRequest::PotongRequestCreate(\Auth::user()->id);
            } else {
                $potongRequest = $checkPotongRequest->id;
            }
            for ($i=0; $i < $request->jumlah_data; $i++) { 

                $potongRequestDetail = GudangPotongRequestDetail::createGudangPotongRequestDetail($potongRequest, $request['jenisBaju'][$i], $request['ukuranBaju'][$i], $request['jumlahBaju'][$i]);
            }

            if ($potongRequestDetail == 1) {
                return redirect('GBarangJadi/requestPotong');
            }
        }else{
            return redirect('GBarangJadi/requestPotong/create');
        }
    }

    public function gRequestPotongupdate($id)
    {
        $potongRequest = GudangPotongRequest::where('id', $id)->first();
        $potongRequestDetail = GudangPotongRequestDetail::where('gdPotongReqId', $potongRequest->id)->get();

        return view('gudangBarangJadi.requestPotong.update', ['request' => $potongRequest, 'potongRequestDetail' => $potongRequestDetail]);
    }

    public function gRequestPotongUpdateSave(Request $request)
    {
        if ($request->jumlah_data != 0) {
            $checkPotongRequest = GudangPotongRequest::where('id', $request->requestId)->first();
            
            for ($i=0; $i < $request->jumlah_data; $i++) { 

                $potongRequestDetail = GudangPotongRequestDetail::createGudangPotongRequestDetail($checkPotongRequest->id, $request['jenisBaju'][$i], $request['ukuranBaju'][$i], $request['jumlahBaju'][$i]);
            }

            if ($potongRequestDetail == 1) {
                return redirect('GBarangJadi/requestPotong');
            }
        }else{
            return redirect('GBarangJadi/requestPotong/update/' . $request->requestId);
        }
    }

    public function gRequestPotongUpdateDelete($requestId, $requestDetailId)
    {
        $RequestPotongDetail = GudangPotongRequestDetail::where('id', $requestDetailId)->delete();

        $checkRequestPotong = GudangPotongRequest::where('id', $requestId)->first();
        $checkRequestPotongDetail = GudangPotongRequestDetail::where('gdPotongReqId', $checkRequestPotong->id)->get();

        if (count($checkRequestPotongDetail) == 0) {
            $RequestPotong = GudangPotongRequest::where('id', $requestId)->delete();

            if ($RequestPotong == 1) {
                return redirect('GBarangJadi/requestPotong');
            }

        }else{
            if ($RequestPotongDetail == 1) {
                return redirect('GBarangJadi/requestPotong/update/' . $requestId);
            }
        }
    }

    public function gRequestPotongDelete(Request $request)
    {
        $requestPotong = GudangPotongRequest::where('id', $request->requestId)->first();
        $requestPotongDetail = GudangPotongRequestDetail::where('gdPotongReqId', $requestPotong->id)->get();
        if (count($requestPotongDetail) != 0) {
            $requestPotongDetail = GudangPotongRequestDetail::where('gdPotongReqId', $requestPotong->id)->delete();
            if ($requestPotongDetail) {
                GudangPotongRequest::where('id', $request->requestId)->delete();
                return redirect('GBarangJadi/requestPotong');
            }
        }else{
            GudangPotongRequest::where('id', $request->requestId)->delete();
            return redirect('GBarangJadi/requestPotong');
        }
    }

}
