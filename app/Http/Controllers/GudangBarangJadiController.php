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
            $detail->pcs = $detail->qty*12;
        }

        return view('gudangBarangJadi.requestPotong.detail', ['gdPotongRequest' => $gdPotongRequest, 'gdPotongRequestDetail' => $gdPotongRequestDetail]);
    }

    public function gRequestPotongcreate()
    {
        return view('gudangBarangJadi.requestPotong.create');
    }

}
