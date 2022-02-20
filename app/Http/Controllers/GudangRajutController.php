<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangRajutKeluar;
use App\Models\GudangRajutKeluarDetail;
use App\Models\gudangRajutMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangStokOpname;
use App\Models\MaterialModel;

class GudangRajutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangRajutKeluar::all();
        $gudangMasuk = gudangRajutMasuk::all();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangRajut.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Rajut Request */
    public function gudangRajutRequest(){
        $gRajutRequest = GudangRajutKeluar::all();
        foreach ($gRajutRequest as $request) {
            $cekPengembalian = gudangRajutMasuk::where('gdRajutKId', $request->id)->first();
            if ($cekPengembalian != null) {
                $request->cekPengembalian = 1;
            }
        }

        return view('gudangRajut.request.index', ['gRajutRequest' => $gRajutRequest]);
    }

    public function RDetail($id){
        $gRajutRequest = GudangKeluar::where('gudangRequest', 'Gudang Rajut')->where('id', $id)->first();
        $gRajutRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangRajut.request.detail', ['gudangKeluar' => $gRajutRequest, 'gudangKeluarDetail' => $gRajutRequestDetail]);
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
        
        $stokOpnameId = GudangStokOpname::CheckStokOpnameData($request);
                
        for ($i=0; $i < count($stokOpnameId); $i++) { 
            $request['gudangStokId'] = "$stokOpnameId[$i]";
            $request['gudangRequest'] = "Gudang Rajut";
            $pengembalian = GudangMasuk::createBarangKembali($request); 
        }       
        if ($pengembalian) {
            $gudangMasukId = $pengembalian;
            $detailPengembalian = GudangKeluarDetail::where('gudangKeluarId', $request['id'])->get();        

            for ($i=0; $i < count($detailPengembalian); $i++) { 
                GudangMasukDetail::createBarangKembaliDetail($gudangMasukId, $request['gudangStokId'], $detailPengembalian[$i]);
            }

            return redirect('gudangRajut/Kembali');
        }
    }
    /* END Gudang Rajut Request */

    /* Gudang Rajut Kembali */
    public function gudangRajutKembali(){
        $gRajutKembali = GudangMasuk::where('gudangRequest', 'Gudang Rajut')->get();
        return view('gudangRajut.kembali.index', ['gRajutKembali' => $gRajutKembali]);
    }

    public function KDetail($id){
        $gRajutKembali = GudangMasuk::where('gudangRequest', 'Gudang Rajut')->where('id', $id)->first();
        $gRajutKembaliDetail = GudangMasukDetail::where('gudangMasukId', $id)->get();

        return view('gudangRajut.kembali.detail', ['gudangMasuk' => $gRajutKembali, 'gudangMasukDetail' => $gRajutKembaliDetail]);
    }

    /* END Gudang Rajut Kembali */
}
