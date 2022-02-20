<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangRajutKeluar;
use App\Models\GudangRajutKeluarDetail;
use App\Models\GudangRajutMasuk;
use App\Models\GudangRajutMasukDetail;
use App\Models\GudangBahanBaku;
use App\Models\MaterialModel;

class GudangRajutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangRajutKeluar::all();
        $gudangMasuk = GudangRajutMasuk::all();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangRajut.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Rajut Request */
    public function gudangRajutRequest(){
        $gRajutRequest = GudangRajutKeluar::all();
        foreach ($gRajutRequest as $request) {
            $cekPengembalian = GudangRajutMasuk::where('gdRajutKId', $request->id)->first();
            if ($cekPengembalian != null) {
                $request->cekPengembalian = 1;
            }
        }

        return view('gudangRajut.request.index', ['gRajutRequest' => $gRajutRequest]);
    }

    public function RDetail($id){        
        $gRajutRequest = GudangRajutKeluar::where('id', $id)->first();
        $gRajutRequestDetail = GudangRajutKeluarDetail::where('gdRajutKId', $id)->get();
        foreach ($gRajutRequestDetail as $value) {
           $material = $value->material->nama;
        }

        return view('gudangRajut.request.detail', ['material' => $material, 'gudangKeluar' => $gRajutRequest, 'gudangKeluarDetail' => $gRajutRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $statusDiterima = 1;  

        $gudangRajutTerima = GudangRajutKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangRajutTerima == 1) {
            return redirect('gudangRajut/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gRajutRequest = GudangRajutKeluar::where('id', $id)->first();
        $gRajutRequestDetail = GudangRajutKeluarDetail::where('gdRajutKId', $gRajutRequest->id)->get();
        

        return view('gudangRajut.kembali.create', ['materials' => $materials, 'gRajutRequest' => $gRajutRequest, 'gRajutRequestDetail' => $gRajutRequestDetail]);
    }

    public function Rstore(Request $request){   
        $gdRajutMasuk = GudangRajutMasuk::CreateRajutMasuk($request->gdRajutKId);
        if ($gdRajutMasuk) {
            for ($i=1; $i <= $request->totalData; $i++) { 
                for ($j=0; $j < count($request["gramasi_".$i]); $j++) { 
                    $gudangRajutMasuk = GudangRajutMasukDetail::CreateRajutMasukDetail($request["gudangId_".$i], $gdRajutMasuk, $request["purchaseId_".$i], $request->materialId, $request->jenisId, $request["gramasi_".$i][$j], $request["diameter_".$i][$j], $request["berat_".$i][$j], $request["qty_".$i][$j]);
                }                     
            } 

            if ($gudangRajutMasuk == 1) {
                return redirect('gudangRajut/Kembali');
            }
        }
    }
    /* END Gudang Rajut Request */

    /* Gudang Rajut Kembali */
    public function gudangRajutKembali(){
        $gRajutKembali = GudangRajutMasuk::all();
        return view('gudangRajut.kembali.index', ['gRajutKembali' => $gRajutKembali]);
    }

    public function KDetail($id){
        $gRajutKembali = GudangRajutMasuk::where('id', $id)->first();
        $gRajutKembaliDetail = GudangRajutMasukDetail::where('gdRajutMId', $id)->get();

        return view('gudangRajut.kembali.detail', ['gudangMasuk' => $gRajutKembali, 'gudangMasukDetail' => $gRajutKembaliDetail]);
    }

    /* END Gudang Rajut Kembali */
}
