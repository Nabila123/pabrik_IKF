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
    
    public function gOperator()
    {
        return view('gudangBarangJadi.operator.index');

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
