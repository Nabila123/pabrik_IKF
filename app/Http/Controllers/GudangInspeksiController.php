<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangInspeksiKeluar;
use App\Models\GudangInspeksiKeluarDetail;
use App\Models\GudangInspeksiMasuk;
use App\Models\GudangInspeksiMasukDetail;
use App\Models\MaterialModel;
use App\Models\AdminPurchase;
use App\Models\AdminPurchaseDetail;
use App\Models\gudangInspeksiStokOpname;
use App\Models\gudangInspeksiStokOpnameDetail;

class GudangInspeksiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $gudangKeluar = GudangInspeksiKeluar::all();
        $gudangMasuk = GudangInspeksiMasuk::all();
        $data = gudangInspeksiStokOpname::all();
        $materials = MaterialModel::where('id', 3)->get();

        $dataStok=[];
        foreach ($materials as $key => $material) {
            $dataStok[$material->id]['id'] = $material->id;
            $dataStok[$material->id]['nama'] = $material->nama;
            $dataStok[$material->id]['qty'] = 0;
        }
        foreach ($data as $key => $value) {
            $dataStok[$value->materialId]['qty'] = $dataStok[$value->materialId]['qty'] + $value->qty;
        }

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangInspeksi.index', ['dataStok' => $dataStok, 'keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Inspeksi Request */
    public function gudangInspeksiRequest(){
        $gInspeksiRequest = GudangInspeksiKeluar::all();
        foreach ($gInspeksiRequest as $request) {
            $cekDetail = GudangInspeksiKeluarDetail::select('purchaseId')->where('gdInspeksiKId', $request->id)->get();

            foreach ($cekDetail as $detail) {
                $gudangInspeksi = gudangInspeksiStokOpname::where('purchaseId', $detail->purchaseId)->first();
                $cekPengembalian = GudangInspeksiMasuk::where('gdInspeksiKId', $request->id)->first();
                
                if ($gudangInspeksi != null) {
                    $request->cekInspeksi = 1;
                }
                if ($cekPengembalian != null) {
                    $request->cekPengembalian = 1;
                }
            }
        }

        return view('gudangInspeksi.request.index', ['gInspeksiRequest' => $gInspeksiRequest]);
    }

    public function RDetail($id){
        $gInspeksiRequest = GudangInspeksiKeluar::where('id', $id)->first();
        $gInspeksiRequestDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId', $id)->get();

        return view('gudangInspeksi.request.detail', ['gudangKeluar' => $gInspeksiRequest, 'gudangKeluarDetail' => $gInspeksiRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $statusDiterima = 1;  

        $gudangInspeksiTerima = GudangInspeksiKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangInspeksiTerima == 1) {
            return redirect('gudangInspeksi/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gInspeksiRequest = GudangInspeksiKeluar::where('id', $id)->first();
        $gInspeksiRequestDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId', $id)->get();

        foreach ($gInspeksiRequestDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }

        return view('gudangInspeksi.kembali.create', ['purchaseId' => $purchaseId, 'materials' => $materials, 'gInspeksiRequest' => $gInspeksiRequest, 'gInspeksiRequestDetail' => $gInspeksiRequestDetail]);
    }

    public function Rstore(Request $request){
        
        $gInspeksiRequest = GudangInspeksiKeluar::where('id', $request->id)->first();
        $gInspeksiRequestDetail = GudangInspeksiKeluarDetail::where('gdInspeksiKId', $gInspeksiRequest->id)->get();
        // dd($gInspeksiRequestDetail);

        if ($gInspeksiRequestDetail != null) {
            $gdInspeksiMasuk = GudangInspeksiMasuk::CreateInspeksiMasuk($gInspeksiRequest->id);
            if ($gdInspeksiMasuk) {
                foreach ($gInspeksiRequestDetail as $detail) {
                    // $gdBahanBaku = GudangBahanBaku::CheckBahanBakuForCompact($detail->gudangId, $detail->purchaseId, $request->materialId, $detail->diameter, $detail->gramasi, 0, $detail->berat, 0, 0, $request->satuan, 0, 0, null);
                    // if ($gdBahanBaku == 1) {
                     $gudangInspeksiDetail = GudangInspeksiMasukDetail::CreateInspeksiMasukDetail($detail->gudangId, $detail->gdDetailMaterialId, $gdInspeksiMasuk, $detail->purchaseId, $detail->materialId, $detail->materialId, $detail->gramasi, $detail->diameter, $detail->berat, $detail->qty);
                    // }
                }

                if ($gudangInspeksiDetail == 1) {
                    return redirect('gudangInspeksi/Kembali');
                }
            }
        }        
    }
    /* END Gudang Inspeksi Request */

    /* Gudang Inspeksi Proses */
    public function gudangInspeksiProses(){
        $gudangInspeksi = gudangInspeksiStokOpname::all();

        return view('gudangInspeksi.proses.index', ['gudangInspeksi' => $gudangInspeksi]);
    }

    public function PCreate()
    {
        $purchaseInspeksi = [];
        $purchaseMaterial = [];
        $index = 0;
        $gudangKeluar = GudangInspeksiKeluar::select('id', 'statusDiterima')->get();
        foreach ($gudangKeluar as $value) {
            $gudangKeluarDetail = GudangInspeksiKeluarDetail::select('purchaseId','gdDetailMaterialId','materialId', 'jenisId')->where('gdInspeksiKId', $value->id)->first();
            $purchaseInspeksi[$index] = [
                "id" => $gudangKeluarDetail->purchaseId,
                "kode" => $gudangKeluarDetail->purchase->kode,              
                "terima" => $value->statusDiterima              
            ];
            
            $purchaseMaterial = [
                "gdDetailMaterialId" => $gudangKeluarDetail->gdDetailMaterialId,
                "materialId" => $gudangKeluarDetail->materialId,
                "jenisId" => $gudangKeluarDetail->jenisId
            ];

            $index++;
        }       

        
        return view('gudangInspeksi.proses.create', ['purchaseId' => $purchaseInspeksi, 'gudangKeluar' => $purchaseMaterial]);
    }

    public function PStore(Request $request)
    {
        // dd($request);
        $gudangInspeksi = gudangInspeksiStokOpname::createInspeksiProses($request->gdDetailMaterialId, $request->purchaseId, $request->materialId, $request->jenisId, $request->gramasi, $request->diameter, date('Y-m-d H:i:s'), \Auth::user()->id);

        if ($gudangInspeksi) {
            $inspeksiId = $gudangInspeksi;

            for ($i = 0; $i < $request->jumlah_data; $i++) {
                gudangInspeksiStokOpnameDetail::createInspeksiProsesDetail($inspeksiId, $request->roll[$i], $request->berat[$i], $request->panjang[$i], $request->lubang[$i], $request->plex[$i], $request->belang[$i], $request->tanah[$i], $request->sambung[$i], $request->jarum[$i], $request->ket[$i]);                       
            }
            return redirect('gudangInspeksi/proses');
        }
    }

    public function PDetail($id)
    {
        $gudangInspeksi = gudangInspeksiStokOpname::where('id', $id)->first();
        $gudangInspeksiDetail = gudangInspeksiStokOpnameDetail::where('gdInspeksiStokId', $id)->get();

        return view('gudangInspeksi.proses.detail', ['gudangInspeksi' => $gudangInspeksi, 'gudangInspeksiDetail' => $gudangInspeksiDetail]);
    }

    public function PUpdate($id)
    {
        $gudangInspeksi = gudangInspeksiStokOpname::where('id', $id)->first();
        $gudangInspeksiDetail = gudangInspeksiStokOpnameDetail::where('gudangInspeksiStokId', $id)->get();

        return view('gudangInspeksi.proses.update', ['gudangInspeksi' => $gudangInspeksi, 'gudangInspeksiDetail' => $gudangInspeksiDetail]);
    }

    public function PUpdateInspeksi(Request $request)
    {
        if ($request->jumlah_data > 0) {
            for ($i = 0; $i < $request->jumlah_data; $i++) {
                gudangInspeksiStokOpnameDetail::createInspeksiProsesDetail($request->id, $request->roll[$i], $request->berat[$i], $request->panjang[$i], $request->lubang[$i], $request->plex[$i], $request->belang[$i], $request->tanah[$i], $request->sambung[$i], $request->jarum[$i], $request->ket[$i]);                       
            }
            return redirect('gudangInspeksi/proses');
        } else {
            return redirect('gudangInspeksi/proses');
        }
        
    }

    public function PUpdateDelete($detailId, $inspeksiId)
    {
        $inspeksiDetail = gudangInspeksiStokOpnameDetail::where('id', $detailId)->delete();
        if ($inspeksiDetail) {
            return redirect('gudangInspeksi/proses/update/' . $inspeksiId . '');
        }
    }

    public function PDelete(Request $request)
    {
        gudangInspeksiStokOpnameDetail::where('gudangInspeksiStokId', $request['inspeksiId'])->delete();        
        gudangInspeksiStokOpname::where('id', $request['inspeksiId'])->delete();   
                
        return redirect('gudangInspeksi/proses');
    }
    /* END Gudang Inspeksi Proses */

    /* Gudang Inspeksi Kembali */
    public function gudangInspeksiKembali(){
        $gInspeksiKembali = GudangInspeksiMasuk::all();
        return view('gudangInspeksi.kembali.index', ['gInspeksiKembali' => $gInspeksiKembali]);
    }

    public function KDetail($id){
        $gInspeksiKembali = GudangInspeksiMasuk::where('id', $id)->first();
        $gInspeksiKembaliDetail = GudangInspeksiMasukDetail::where('gdInspeksiMId', $id)->get();


        return view('gudangInspeksi.kembali.detail', ['gudangMasuk' => $gInspeksiKembali, 'gudangMasukDetail' => $gInspeksiKembaliDetail]);
    }
    /* END Gudang Inspeksi Kembali */
}
