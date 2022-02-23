<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PPICGudangRequest;
use App\Models\PPICGudangRequestDetail;
use App\Models\MaterialModel;

class PPICController extends Controller
{
    public function index()
    {
        return view('ppic.index');
    }

    public function gdRequest()
    {
        $ppicRequest = PPICGudangRequest::all();
        return view('ppic.gudangRequest.index', ['ppicRequest' => $ppicRequest]);
    }

    public function gdRequestCreate()
    {
        $materials = MaterialModel::where('keterangan', 'Bahan Baku')->get();
        return view('ppic.gudangRequest.create', ['materials' => $materials]);
    }

    public function gdRequestStore(Request $request)
    {
        for ($i=0; $i < $request->jumlah_data; $i++) { 
            $checkPPICRequest = PPICGudangRequest::where('gudangRequest', $request['gudangRequest'][$i])
                                                 ->where('tanggal', date('Y-m-d'))
                                                 ->where('statusDiterima', 0)
                                                 ->first();
            if ($checkPPICRequest == null) {
                $ppicRequest = PPICGudangRequest::CreatePPICGudangRequest($request['gudangRequest'][$i]);
                if ($ppicRequest != 0) {
                    $ppicRequestDetail = PPICGudangRequestDetail::CreatePPICGudangRequestDetail($ppicRequest, $request['materialId'][$i], $request['materialId'][$i], $request['gramasi'][$i], $request['diameter'][$i], $request['qty'][$i]);
                }
            }else{
                $ppicRequestDetail = PPICGudangRequestDetail::CreatePPICGudangRequestDetail($checkPPICRequest->id, $request['materialId'][$i], $request['materialId'][$i], $request['gramasi'][$i], $request['diameter'][$i], $request['qty'][$i]);
            }
        }

        if ($ppicRequestDetail == 1) {
            return redirect('ppic/Gudang');
        }
        
    }

    public function gdRequestDetail($id)
    {
        $ppicRequest = PPICGudangRequest::where('id', $id)->first();
        $ppicRequestDetail = PPICGudangRequestDetail::where('gdPpicRequestId', $ppicRequest->id)->get();

        return view('ppic.gudangRequest.detail', ['ppicRequest' => $ppicRequest, 'ppicRequestDetail' => $ppicRequestDetail]);
    }   

    public function gdRequestUpdate($id)
    {
        $materials = MaterialModel::where('keterangan', 'Bahan Baku')->get();
        $ppicRequest = PPICGudangRequest::where('id', $id)->first();
        $ppicRequestDetail = PPICGudangRequestDetail::where('gdPpicRequestId', $ppicRequest->id)->get();

        return view('ppic.gudangRequest.update', ['materials' => $materials, 'ppicRequest' => $ppicRequest, 'ppicRequestDetail' => $ppicRequestDetail]);
    }

    public function gdRequestUpdateStore($id, Request $request)
    {
        $ppicRequest = PPICGudangRequest::where('id', $id)->first();
        for ($i=0; $i < $request->jumlah_data; $i++) {
            $ppicRequestDetail = PPICGudangRequestDetail::CreatePPICGudangRequestDetail($ppicRequest->id, $request['materialId'][$i], $request['materialId'][$i], $request['gramasi'][$i], $request['diameter'][$i], $request['qty'][$i]);
        }

        return redirect('ppic/Gudang');
    }

    public function gdRequestDetailDelete($detailId, $ppicRequestId)
    {
        $ppicRequestDetail = PPICGudangRequestDetail::where('id', $detailId)->delete();
        if ($ppicRequestDetail) {
            return redirect('ppic/Gudang/Update/' . $ppicRequestId . '');
        }
    }

    public function gdRequestDelete(Request $request)
    {
        PPICGudangRequestDetail::where('gdPpicRequestId', $request['ppicRequestId'])->delete();        
        PPICGudangRequest::where('id', $request['ppicRequestId'])->delete();   
                
        return redirect('ppic/Gudang');
    }
}
