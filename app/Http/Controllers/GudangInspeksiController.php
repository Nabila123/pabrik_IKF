<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangKeluar;
use App\Models\GudangKeluarDetail;
use App\Models\GudangMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangStokOpname;
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
        $gudangKeluar = GudangKeluar::where('gudangRequest', 'Gudang Inspeksi')->get();
        $gudangMasuk = GudangMasuk::where('gudangRequest', 'Gudang Inspeksi')->get();
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
        $gInspeksiRequest = GudangKeluar::where('gudangRequest', 'Gudang Inspeksi')->get();
        foreach ($gInspeksiRequest as $request) {
            $cekDetail = GudangKeluarDetail::select('purchaseId')->where('gudangKeluarId', $request->id)->get();

            foreach ($cekDetail as $detail) {
                $gudangInspeksi = gudangInspeksiStokOpname::where('purchaseId', $detail->purchaseId)->first();
                $cekPengembalian = GudangMasuk::where('gudangRequest', 'Gudang Inspeksi')->where('gudangKeluarId', $request->id)->first();
                
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
        $gInspeksiRequest = GudangKeluar::where('gudangRequest', 'Gudang Inspeksi')->where('id', $id)->first();
        $gInspeksiRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangInspeksi.request.detail', ['gudangKeluar' => $gInspeksiRequest, 'gudangKeluarDetail' => $gInspeksiRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $gudangRequest = 'Gudang Inspeksi';  
        $statusDiterima = 1;  

        $gudangInspeksiTerima = GudangKeluar::updateStatusDiterima($id, $gudangRequest, $statusDiterima);

        if ($gudangInspeksiTerima == 1) {
            return redirect('gudangInspeksi/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gInspeksiRequest = GudangKeluar::where('gudangRequest', 'Gudang Inspeksi')->where('id', $id)->first();
        $gInspeksiRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        foreach ($gInspeksiRequestDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }

        return view('gudangInspeksi.kembali.create', ['purchaseId' => $purchaseId, 'materials' => $materials, 'gInspeksiRequest' => $gInspeksiRequest, 'gInspeksiRequestDetail' => $gInspeksiRequestDetail]);
    }

    public function Rstore(Request $request){
        $stokOpnameId = GudangStokOpname::CheckStokOpnameData($request);
                
        for ($i=0; $i < count($stokOpnameId); $i++) { 
            $request['gudangStokId'] = "$stokOpnameId[$i]";
            $request['gudangRequest'] = "Gudang Inspeksi";
            $pengembalian = GudangMasuk::createBarangKembali($request); 
        }
               
        if ($pengembalian) {
            $gudangMasukId = $pengembalian;
            $detailPengembalian = GudangKeluarDetail::where('gudangKeluarId', $request['id'])->get();        

            for ($i=0; $i < count($detailPengembalian); $i++) { 
                GudangMasukDetail::createBarangKembaliDetail($gudangMasukId, $request['gudangStokId'], $detailPengembalian[$i]);
            }

            return redirect('gudangInspeksi/Kembali');
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
        $gudangKeluar = GudangKeluar::select('id', 'materialId', 'jenisId', 'statusDiterima')->where('gudangRequest','Gudang Inspeksi')->get();
        foreach ($gudangKeluar as $value) {
            $gudangKeluarDetail = GudangKeluarDetail::select('purchaseId','gudangStokId')->where('gudangKeluarId', $value->id)->first();
            
            $purchaseInspeksi[$index] = [
                "id" => $gudangKeluarDetail->purchaseId,
                "kode" => $gudangKeluarDetail->purchase->kode,              
                "terima" => $value->statusDiterima              
            ];
            
            $purchaseMaterial = [
                "gudangStokId" => $gudangKeluarDetail->gudangStokId,
                "materialId" => $value->materialId,
                "jenisId" => $value->jenisId
            ];

            $index++;
        }       

        
        return view('gudangInspeksi.proses.create', ['purchaseId' => $purchaseInspeksi, 'gudangKeluar' => $purchaseMaterial]);
    }

    public function PStore(Request $request)
    {
        
        $gudangInspeksi = gudangInspeksiStokOpname::createInspeksiProses($request->gudangStokId, $request->purchaseId, $request->materialId, $request->jenisId, date('Y-m-d H:i:s'), \Auth::user()->id);

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
        $gudangInspeksiDetail = gudangInspeksiStokOpnameDetail::where('gudangInspeksiStokId', $id)->get();

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
        $gInspeksiKembali = GudangMasuk::where('gudangRequest', 'Gudang Inspeksi')->get();
        return view('gudangInspeksi.kembali.index', ['gInspeksiKembali' => $gInspeksiKembali]);
    }

    public function KDetail($id){
        $gInspeksiKembali = GudangMasuk::where('gudangRequest', 'Gudang Inspeksi')->where('id', $id)->first();
        $gInspeksiKembaliDetail = GudangMasukDetail::where('gudangMasukId', $id)->get();


        return view('gudangInspeksi.kembali.detail', ['gudangMasuk' => $gInspeksiKembali, 'gudangMasukDetail' => $gInspeksiKembaliDetail]);
    }
    /* END Gudang Inspeksi Kembali */
}
