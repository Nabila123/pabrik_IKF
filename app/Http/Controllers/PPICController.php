<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\GudangRajutKeluarDetail;
use App\Models\GudangRajutMasuk;
use App\Models\GudangRajutMasukDetail;
use App\Models\GudangCuciKeluarDetail;
use App\Models\GudangCompactKeluar;
use App\Models\GudangCompactMasuk;
use App\Models\GudangCompactMasukDetail;
use App\Models\PPICGudangRequestDetail;
use App\Models\MaterialModel;

class PPICController extends Controller
{
    public function index()
    {        
        $dataStok=[];
        $dataBenang=[];    
        $i = 0;   
        $materials = MaterialModel::all();       
        $bahanBaku = GudangBahanBaku::all();       

        foreach ($materials as $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
            $dataStok[$material->id]['satuan'] = "";

            $data = GudangBahanBakuDetail::where('materialId', $material->id)->get();
            foreach ($data as $value) {
                $dataMaterial = GudangBahanBakuDetailMaterial::where('gudangDetailId', $value->id)->get();
                foreach ($dataMaterial as $detail) {
                    if ($material->id == 1) {
                        $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $detail->netto;
                        $dataStok[$material->id]['satuan'] = "KG";
                    } else {
                        $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $detail->qty;
                        $dataStok[$material->id]['satuan'] = "Roll";
                    }
                    
                }
            }
        }

        foreach ($bahanBaku as $value) {            
            $rajutKeluarDetail = GudangRajutKeluarDetail::where('gudangId', 1)->where('materialId', 1)->get();
            foreach ($rajutKeluarDetail as $rajutKeluar) {
                $gdrajutMasuk = GudangRajutMasuk::where('gdRajutKId', $rajutKeluar->gdRajutKId)->get();
                // dd($gdrajutMasuk);
                foreach ($gdrajutMasuk as $gdRmasuk) {
                    $rajutMasukDetail = GudangRajutMasukDetail::where('gudangId', $value->id)->where('gdRajutMId', $gdRmasuk->id)->get();
                    foreach ($rajutMasukDetail as $rajutMasuk) {
                        $cuciKeluarDetail = GudangCuciKeluarDetail::where('gudangId', $value->id)->where('gdDetailMaterialId', $rajutMasuk->gdDetailMaterialId)->get();
                        // dd($cuciKeluarDetail);
                        foreach ($cuciKeluarDetail as $cuciKeluar) {
                            $compactKeluar = GudangCompactKeluar::where('gdCuciKId', $cuciKeluar->gdCuciKId)->get();
                            foreach ($compactKeluar as $keluarCompact) {
                                $compactMasuk = GudangCompactMasuk::where('gdCompactKId', $keluarCompact->id)->get();
                                foreach ($compactMasuk as $masukCompact) {
                                    $compactMasukDetail = GudangCompactMasukDetail::where('gudangId', $value->id)->where('gdCompactMId', $masukCompact->id)->get();                       
                                    foreach ($compactMasukDetail as $masukCompactDetail) {
                                        $detailMaterial = GudangBahanBakuDetailMaterial::where('id', $masukCompactDetail->gdDetailMaterialId)->get();
                                        foreach ($detailMaterial as $materialDetail) {
                                            $dataBenang[$i] = [
                                                'purchaseId'     => $value->purchase->kode,
                                                'materialOld'    => $rajutKeluar->material->nama,
                                                'materialNew'    => $masukCompactDetail->material->nama,
                                                'diameter'       => $materialDetail->diameter,
                                                'gramasi'        => $materialDetail->gramasi,
                                                'qty'            => $materialDetail->qty,
                                            ];

                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return view('ppic.index')->with(['dataStok'=>$dataStok, 'dataBenang'=>$dataBenang]);
    }

    public function gdRequest()
    {
        $ppicUpdateStatus = PPICGudangRequest::where('statusDiterima', 1)->get();
        foreach ($ppicUpdateStatus as $val) {
            PPICGudangRequest::updateStatusDiterima($val->id, 2);
        }
        
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
