<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminPurchase;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetail;
use App\Models\GudangMasuk;
use App\Models\GudangKeluar;

class GudangBahanBakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = GudangBahanBakuDetail::all();
        $dataStok=[];
        $dataStok[1]['barang']  = 'Benang';
        $dataStok[2]['barang']  = 'Grey';
        $dataStok[3]['barang']  = 'Kain';
        $dataStok[1]['qty']  = 0;
        $dataStok[2]['qty']  = 0;
        $dataStok[3]['qty']  = 0;
        foreach ($data as $key => $value) {
            if($value->material->jenisId == 1){
                $dataStok[1]['qty'] = $dataStok[1]['qty'] + $value->qty;
            }
            if($value->material->jenisId == 2){
                $dataStok[2]['qty'] = $dataStok[2]['qty'] + $value->qty;
            }
            if($value->material->jenisId == 3){
                $dataStok[3]['qty'] = $dataStok[3]['qty'] + $value->qty;
            }
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
        }

        return redirect('bahan_baku/supply');

    }

    public function delete(Request $request)
    {
        $gudangDetail = GudangBahanBakuDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangBahanBaku::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('bahan_baku/supply');
    }

    public function masukGudang()
    {
        $data = GudangMasuk::all();
        return view('bahanBaku.masuk.index')->with(['data'=>$data]);
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
        }

        return redirect('bahan_baku/supply');

    }

    public function deleteMasukGudang(Request $request)
    {
        $gudangDetail = GudangMasukDetail::where('gudangId', $request['gudangId'])->delete();

        if ($gudangDetail) {
            GudangMasuk::where('id', $request['gudangId'])->delete();
        }
                
        return redirect('bahan_baku/masuk');
    }

    public function keluarGudang()
    {
        $data = GudangKeluar::all();
        return view('bahanBaku.keluar.index')->with(['data'=>$data]);
    }
}
