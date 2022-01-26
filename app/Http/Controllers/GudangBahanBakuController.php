<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangKeluar;
use App\Models\GudangKeluarDetail;
use App\Models\GudangStokOpname;
use App\Models\MaterialModel;

class GudangBahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = GudangStokOpname::all();
        $materials = MaterialModel::all();
        $dataStok=[];
        foreach ($materials as $key => $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
        }
        foreach ($data as $key => $value) {
            $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $value->qty;
        }
    
        $dataMasuk = GudangMasuk::count();
        $dataKeluar = GudangKeluar::count();
        return view('bahanBaku.index')->with(['dataStok'=>$dataStok,'dataMasuk'=>$dataMasuk,'dataKeluar'=>$dataKeluar]);
    }

    public function indexSupplyBarang(){
        $data = GudangBahanBaku::all();
        return view('bahanBaku.supply.index')->with(['data'=>$data]);
    }

    public function create()
    {
        $purchases = AdminPurchase::all();
        return view('bahanBaku.supply.create')->with(['purchases'=>$purchases]);
    }

    public function store(Request $request)
    {
        $jumlahData = $request['jumlah_data'];
        $bahanBaku = new GudangBahanBaku;
        $bahanBaku->kodePurchase = $request['kodePurchase'];
        $bahanBaku->namaSuplier = $request['suplier'];
        $bahanBaku->diameter = $request['diameter'];
        $bahanBaku->gramasi = $request['gramasi'];
        $bahanBaku->total = $request['total'];
        $bahanBaku->userId = \Auth::user()->id;

        //get purchaseId
        $purchase = AdminPurchase::where('kode',$request['kodePurchase'])->first();

        if($bahanBaku->save()){
            for ($i=0; $i < $jumlahData; $i++) { 
                $bahanBakuDetail = new GudangBahanBakuDetail;
                $bahanBakuDetail->gudangId = $bahanBaku->id;
                $bahanBakuDetail->materialId = $request['materialId'][$i];
                $bahanBakuDetail->qty = $request['qty'][$i];
                $bahanBakuDetail->brutto = $request['brutto'][$i];
                $bahanBakuDetail->netto = $request['netto'][$i];
                $bahanBakuDetail->tarra = $request['tarra'][$i];
                $bahanBakuDetail->unit = $request['unit'][$i];
                $bahanBakuDetail->unitPrice = $request['unitPrice'][$i];
                $bahanBakuDetail->amount = $request['amount'][$i];
                $bahanBakuDetail->remark = $request['remark'][$i];
                if($bahanBakuDetail->save()){
                    $saveStatus = 1;
                } else {
                    $saveStatus = 0;
                    die();
                }

                //get jenisId
                $material = MaterialModel::find($request['materialId'][$i]);

                //input ke gudang stok opname
                $stokOpname = new GudangStokOpname;
                $stokOpname->purchaseId = $purchase->id;
                $stokOpname->materialId = $request['materialId'][$i];
                $stokOpname->jenisId = $material->jenisId;
                $stokOpname->qty = $request['qty'][$i];
                $stokOpname->userId = \Auth::user()->id;

                $stokOpname->save();
            }
        }

        return redirect('/bahan_baku/supply');
    }

    public function detail($id)
    {
        $data = GudangBahanBaku::find($id);
        $dataDetail = GudangBahanBakuDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.supply.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function edit($id)
    {
        $data = GudangBahanBaku::find($id);
        $dataDetail = GudangBahanBakuDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.supply.update')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function update($id, Request $request)
    {
        $data['kodePurchase'] = $request['kodePurchase'];
        $data['namaSuplier'] = $request['namaSuplier'];
        $data['diameter'] = $request['diameter'];
        $data['gramasi'] = $request['gramasi'];
        $data['total'] = $request['total'];
        $data['userId'] = \Auth::user()->id;

        $updateBahanBaku = GudangBahanBaku::where('id',$id)->update($data);

        //get purchaseId
        $purchase = AdminPurchase::where('kode',$request['kodePurchase'])->first();

        for ($i=0; $i < $request['jumlah_data']; $i++) { 
            $dataDetail['gudangId'] = $id;
            $dataDetail['materialId'] = $request['materialId'][$i];
            $dataDetail['qty'] = $request['qty'][$i];
            $dataDetail['brutto'] = $request['brutto'][$i];
            $dataDetail['netto'] = $request['netto'][$i];
            $dataDetail['tarra'] = $request['tarra'][$i];
            $dataDetail['unit'] = $request['unit'][$i];
            $dataDetail['unitPrice'] = $request['unitPrice'][$i];
            $dataDetail['amount'] = $request['amount'][$i];
            $dataDetail['remark'] = $request['remark'][$i];

            $updateBahanBakuDetail = GudangBahanBakuDetail::where('id',$request['detailId'][$i])->update($dataDetail);

            //get jenisId
            $material = MaterialModel::find($request['materialId'][$i]);

            $dataStokOpname['jenisId'] = $request['jenisId'][$i];
            $dataStokOpname['qty'] = $request['qty'][$i];
            $dataStokOpname['userId'] = \Auth::user()->id;

            $updateStokOpname = GudangStokOpname::where('purchaseId', $purchase->id)->where('materialId',$request['materialId'][$i])->update($dataStokOpname);
        }

        return redirect('bahan_baku/supply');

    }

    public function delete(Request $request)
    {
        $kodePurchase = GudangBahanBaku::find($request['gudangId']);
        $purchase = AdminPurchase::where('kode',$kodePurchase->kodePurchase)->first();
        $delStokOpname = GudangStokOpname::where('purchaseId',$purchase->id)->delete();

        $gudangDetail = GudangBahanBakuDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangBahanBaku::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('bahan_baku/supply');
    }

    public function keluarGudang()
    {
        $data = GudangKeluar::all();
        return view('bahanBaku.keluar.index')->with(['data'=>$data]);
    }

    public function createKeluarGudang()
    {
        $datas = GudangStokOpname::all();
        $dataMaterial=[];
        foreach ($datas as $key => $value) {
            $dataMaterial[]=$value->material;
        }
        return view('bahanBaku.keluar.create')->with(['dataMaterial'=>$dataMaterial]);
    }

    public function getDataJenis($materialId)
    {
        $datas = MaterialModel::find($materialId);
    
        return json_encode($datas->jenisId);
    }

    public function getDataPurchase($materialId)
    {
        $datas = GudangStokOpname::where('materialId',$materialId)->get();
        $dataPurchase = [];
        foreach($datas as $data){
            $dataPurchase[] = $data->purchase;
        }

        return json_encode($dataPurchase);
    }


    public function masukGudang()
    {
        $data = GudangMasuk::all();
        return view('bahanBaku.masuk.index')->with(['data'=>$data]);
    }

    public function createMasukGudang()
    {
        $purchases = AdminPurchase::all();
        return view('bahanBaku.masuk.create')->with(['purchases'=>$purchases]);
    }

    public function storeMasukGudang(Request $request)
    {
        $jumlahData = $request['jumlah_data'];
        $bahanBaku = new GudangMasuk;
        $bahanBaku->kodePurchase = $request['kodePurchase'];
        $bahanBaku->namaSuplier = $request['suplier'];
        $bahanBaku->diameter = $request['diameter'];
        $bahanBaku->gramasi = $request['gramasi'];
        $bahanBaku->total = $request['total'];
        $bahanBaku->userId = \Auth::user()->id;

        if($bahanBaku->save()){
            for ($i=0; $i < $jumlahData; $i++) { 
                $bahanBakuDetail = new GudangMasukDetail;
                $bahanBakuDetail->gudangId = $bahanBaku->id;
                $bahanBakuDetail->materialId = $request['materialId'][$i];
                $bahanBakuDetail->qty = $request['qty'][$i];
                $bahanBakuDetail->brutto = $request['brutto'][$i];
                $bahanBakuDetail->netto = $request['netto'][$i];
                $bahanBakuDetail->tarra = $request['tarra'][$i];
                $bahanBakuDetail->unit = $request['unit'][$i];
                $bahanBakuDetail->unitPrice = $request['unitPrice'][$i];
                $bahanBakuDetail->amount = $request['amount'][$i];
                $bahanBakuDetail->remark = $request['remark'][$i];
                if($bahanBakuDetail->save()){
                    $saveStatus = 1;
                } else {
                    $saveStatus = 0;
                    die();
                }
            }
        }

        return redirect('/bahan_baku/masuk');
    }

    public function detailMasukGudang($id)
    {
        $data = GudangMasuk::find($id);
        $dataDetail = GudangMasukDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.masuk.detail')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function editMasukGudang($id)
    {
        $data = GudangMasuk::find($id);
        $dataDetail = GudangMasukDetail::where('gudangId',$id)->get();
        foreach ($dataDetail as $key => $value) {
            $value->materialNama = $value->material->nama;
        }

        return view('bahanBaku.masuk.update')->with(['data'=>$data,'dataDetail'=>$dataDetail]);
    }

    public function updateMasukGudang($id, Request $request)
    {
        $data['kodePurchase'] = $request['kodePurchase'];
        $data['namaSuplier'] = $request['namaSuplier'];
        $data['diameter'] = $request['diameter'];
        $data['gramasi'] = $request['gramasi'];
        $data['total'] = $request['total'];
        $data['userId'] = \Auth::user()->id;

        $updateBahanBaku = GudangMasuk::where('id',$id)->update($data);

        for ($i=0; $i < $request['jumlah_data']; $i++) { 
            $dataDetail['gudangId'] = $id;
            $dataDetail['materialId'] = $request['materialId'][$i];
            $dataDetail['qty'] = $request['qty'][$i];
            $dataDetail['brutto'] = $request['brutto'][$i];
            $dataDetail['netto'] = $request['netto'][$i];
            $dataDetail['tarra'] = $request['tarra'][$i];
            $dataDetail['unit'] = $request['unit'][$i];
            $dataDetail['unitPrice'] = $request['unitPrice'][$i];
            $dataDetail['amount'] = $request['amount'][$i];
            $dataDetail['remark'] = $request['remark'][$i];

            $updateBahanBakuDetail = GudangMasukDetail::where('id',$request['detailId'][$i])->update($dataDetail);
        }

        return redirect('bahan_baku/masuk');

    }

    public function deleteMasukGudang(Request $request)
    {
        $gudangDetail = GudangMasukDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangMasuk::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('bahan_baku/masuk');
    }

}
