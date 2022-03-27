<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangPotongKeluar;
use App\Models\GudangPotongKeluarDetail;
use App\Models\GudangPotongProses;
use App\Models\GudangPotongProsesDetail;
use App\Models\GudangPotongRequest;
use App\Models\GudangPotongRequestDetail;
use App\Models\GudangJahitMasuk;
use App\Models\GudangJahitMasukDetail;

use App\Models\Pegawai;

class GudangPotongController extends Controller
{
    public function index()
    {
        return view('gudangPotong.index');
    }

    public function getData(Request $request)
    {
        $gdPotong = GudangPotongKeluar::getPurchaseWithMaterial($request);

        return json_encode($gdPotong);
    }

    //Gudang Potong Request Bahan Jadi
    public function gRequest()
    {
        $gdPotongReq = GudangPotongRequest::all();
        return view('gudangPotong.request.index', ['gdPotongReq' => $gdPotongReq]);
    }

    public function gReqDetail($id)
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

        return view('gudangPotong.request.detail', ['gdPotongRequest' => $gdPotongRequest, 'gdPotongRequestDetail' => $gdPotongRequestDetail]);
    }

    public function gReqTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangRajutTerima = GudangPotongRequest::updateStatusDiterima($id, $statusDiterima);

        if ($gudangRajutTerima == 1) {
            return redirect('GPotong/request');
        }
    }

    //Gudang Potong Keluar Bahan Baku
    public function gKeluar()
    {
        $gudangPotong = GudangPotongKeluar::all();
        foreach ($gudangPotong as $request) {
            $cekDetail = GudangPotongProses::where('gPotongKId', $request->id)->first();
            if ($cekDetail != null) {
                $cekJahit = GudangJahitMasukDetail::where('gdPotongProsesId', $cekDetail->id)->first();
                if ($cekJahit != null) {
                    $request->cekJahit = 1;
                }
            }
                    
            if ($cekDetail != null) {
                $request->cekPotong = 1;
            }else {
                $request->cekPotong = 0;
            }
            
        }

        return view('gudangPotong.keluar.index', ['gudangPotong' => $gudangPotong]);
    }

    public function gKeluarDetail($id)
    {
        $gudangPotong = GudangPotongKeluar::where('id', $id)->first();
        $gudangPotongDetail = GudangPotongKeluarDetail::where('gdPotongKId', $gudangPotong->id)->get();

        return view('gudangPotong.keluar.detail', ['gudangPotong' => $gudangPotong, 'gudangPotongDetail' => $gudangPotongDetail]);
    }

    public function gKeluarTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangPotongTerima = GudangPotongKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangPotongTerima == 1) {
            return redirect('GPotong/keluar');
        }
    }

    public function gKeluarKembali($id)
    {
        $gPotong = GudangPotongKeluar::where('id', $id)->first();
        $gPotongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId', $id)->get();

        foreach ($gPotongKeluarDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }

        return view('gudangPotong.keluar.create', ['purchaseId' => $purchaseId, 'gPotong' => $gPotong, 'gPotongKeluarDetail' => $gPotongKeluarDetail]);
    }

    public function gKeluarKembaliStore(Request $request)
    {
        $jahitMasuk = new GudangJahitMasuk;
        $jahitMasuk->tanggal = date('Y-m-d');
        $jahitMasuk->userId = \Auth::user()->id;
        $jahitMasuk->created_at = date('Y-m-d H:i:s');
        if ($jahitMasuk->save()) {
            for ($i=0; $i < count($request->purchaseId); $i++) { 
                $gdPotongProses = GudangPotongProses::where('gPotongKId', $request->id)->where('purchaseId', $request['purchaseId'][$i])->first();
                $gdPotongProsesDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $gdPotongProses->id)->get();
                foreach ($gdPotongProsesDetail as $detail) {
                    $gdJahitDetail = GudangJahitMasukDetail::createGudangJahitMasukDetail($jahitMasuk->id, $detail->id, $gdPotongProses->purchaseId, $detail->jenisBaju, $detail->ukuranBaju, $detail->hasilDz);
                }
            }

            if ($gdJahitDetail == 1) {
                return redirect('GPotong/keluar');
            }
        }
    }

    //Gudang Potong Proses
    public function gProses()
    {
        $gudangPotongProses = GudangPotongProses::all();
        return view('gudangPotong.proses.index', ['gdPotongProses' => $gudangPotongProses]);
    }

    public function gProsesCreate()
    {
        $purchasePotong = [];
        $material = [];
        $index = 0;
        $pegawai = Pegawai::where('kodeBagian', 'potong')->get();
        $gudangKeluar = GudangPotongKeluar::all();
        foreach ($gudangKeluar as $keluar) {
            $gudangKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId', $keluar->id)->get();
            // dd($gudangKeluarDetail);
            foreach ($gudangKeluarDetail as $detail) {
                $prosesPotong = GudangPotongProses::where('gPotongKId', $keluar->id)->where('purchaseId', $detail->purchaseId)->first();
                if ($prosesPotong == null) {
                    $data = [
                        "id" => $detail->purchaseId,
                        "kode" => $detail->purchase->kode,              
                        "terima" => $keluar->statusDiterima,
                        "tanggal" => $keluar->tanggal,
                        "gdKeluarId" => $keluar->id,
                    ];
                    if (!in_array($data, $purchasePotong)) {
                        $purchasePotong[$index] = [
                            "id" => $detail->purchaseId,
                            "kode" => $detail->purchase->kode,              
                            "terima" => $keluar->statusDiterima,                         
                            "tanggal" => $keluar->tanggal,
                            "gdKeluarId" => $keluar->id,
                        ];
                        $index++;
                    }
                }

                $material = [
                    "materialId" => $detail->materialId,
                    "jenisId" => $detail->jenisId
                ];
            }
        }

        return view('gudangPotong.proses.create', ['pegawai' => $pegawai, 'purchaseId' => $purchasePotong, 'material' => $material]);
    }

    public function gProsesStore(Request $request)
    {
        // dd($request);
        $dataId = explode("-",$request->purchaseId);
        $gudangPotong = GudangPotongProses::createPotongProses($request->gdPotongKId, $request->pegawaiId, $dataId[0], $request->materialId, $request->jenisId, $request->jumlah, date('Y-m-d H:i:s'), \Auth::user()->id);
        if ($gudangPotong) {
            $potongProsesId = $gudangPotong;

            for ($i = 0; $i < $request->jumlah_data; $i++) {
                GudangPotongProsesDetail::createPotongProsesDetail($potongProsesId, $request->jmlPotong[$i], $request->beratPotong[$i],  $request->diameter, $request->gramasi, $request->beratRoll[$i], $request->jnsBaju[$i], $request->size[$i], $request->totalDZ[$i], $request->totalKG[$i], $request->skb[$i], $request->bs[$i], $request->kecil[$i], $request->ketek[$i], $request->ketekPot[$i], $request->sumbu[$i], $request->bunder[$i], $request->tKecil[$i], $request->tBesar[$i], $request->tangan[$i], $request->kPutih[$i], $request->kBelang[$i], \Auth::user()->id);                
            }
            return redirect('GPotong/proses');
        }
    }

    public function getDataDetailMaterial($purchaseId, $materialId, $diameter, $gramasi = "")
    {
        $dataId = explode("-",$purchaseId);
        $datas = [];
        if ($gramasi == "null") {        
            $potongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId',$dataId[1])->where('purchaseId',$dataId[0])->where('materialId',$materialId)->where('diameter',$diameter)->get();
            foreach ($potongKeluarDetail as $detail) {
                if (!in_array($detail->gramasi, $datas)) {
                    $datas[] = $detail->gramasi;
                }
            }
        }else{
            $potongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId',$dataId[1])->where('purchaseId',$dataId[0])->where('materialId',$materialId)->where('diameter',$diameter)->where('gramasi',$gramasi)->get();
            foreach ($potongKeluarDetail as $detail) {
                $datas['gdPotongKId'] = $detail->gdPotongKeluar->id;
                $datas['jumlah'] = $detail->qty;
            }
        }

        return json_encode($datas);
    }

    public function gProsesDetail($id)
    {
        $gdPotong = GudangPotongProses::find($id);
        $gdPotongDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $gdPotong->id)->get();

        return view('gudangPotong.proses.detail', ['gdPotong' => $gdPotong, 'gdPotongDetail' => $gdPotongDetail]);
    }

    public function gProsesUpdate($id)
    {
        $gdPotongProses = GudangPotongProses::where('id', $id)->first();
        $gdPotongProsesDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $gdPotongProses->id)->get();

        return view('gudangPotong.proses.update', ['gdPotongProses' => $gdPotongProses, 'gdPotongProsesDetail' => $gdPotongProsesDetail]);
    }

    public function gProsesUpdatePotong(Request $request)
    {
       if ($request->jumlah_data > 0) {
            for ($i = 0; $i < $request->jumlah_data; $i++) {
                GudangPotongProsesDetail::createPotongProsesDetail($request->id, $request->jmlPotong[$i], $request->beratPotong[$i],  $request->diameter, $request->gramasi, $request->beratRoll[$i], $request->jnsBaju[$i], $request->size[$i], $request->totalDZ[$i], $request->totalKG[$i], $request->skb[$i], $request->bs[$i], $request->kecil[$i], $request->ketek[$i], $request->ketekPot[$i], $request->sumbu[$i], $request->bunder[$i], $request->tKecil[$i], $request->tBesar[$i], $request->tangan[$i], $request->kPutih[$i], $request->kBelang[$i], \Auth::user()->id);                
            }
            return redirect('GPotong/proses');
        } else {
            return redirect('GPotong/proses');
        }
    }

    public function gProsesUpdateDelete($detailId, $potongProsesId)
    {
        $inspeksiDetail = GudangPotongProsesDetail::where('id', $detailId)->delete();
        if ($inspeksiDetail) {
            return redirect('GPotong/proses/update/' . $potongProsesId . '');
        }
    }

    public function gProsesDelete(Request $request)
    {
        GudangPotongProsesDetail::where('gdPotongProsesId', $request['gdPotongProsesId'])->delete();        
        GudangPotongProses::where('id', $request['gdPotongProsesId'])->delete();   
                
        return redirect('GPotong/proses');
    }
}
