<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangKeluar;
use App\Models\GudangKeluarDetail;
use App\Models\GudangMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangStokOpname;
use App\Models\MaterialModel;


class GudangCompactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('StatusDiterima', '>=', 2)->get();
        $gudangMasuk = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('StatusDiterima', '>=', 3)->get();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangCompact.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Compact Request */
    public function gudangCompactRequest(){
        $gCompactRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('statusDiterima', '>=', '2')->get();
        foreach ($gCompactRequest as $request) {
            $cekPengembalian = GudangMasuk::where('gudangRequest', 'Gudang Compact')->where('gudangKeluarId', $request->id)->first();
            if ($cekPengembalian != null) {
                $request->cekPengembalian = 1;
            }
        }

        return view('gudangCompact.request.index', ['gCompactRequest' => $gCompactRequest]);
    }

    public function RDetail($id){
        $gCompactRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('statusDiterima', '>=', '2')->where('id', $id)->first();
        $gCompactRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangCompact.request.detail', ['gudangKeluar' => $gCompactRequest, 'gudangKeluarDetail' => $gCompactRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $gudangRequest = 'Gudang Cuci';  
        $statusDiterima = 3;  

        $gudangCompactTerima = GudangKeluar::updateStatusDiterima($id, $gudangRequest, $statusDiterima);

        if ($gudangCompactTerima == 1) {
            return redirect('gudangCompact/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gCompactRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $id)->first();
        $gCompactRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        foreach ($gCompactRequestDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }

        return view('gudangCompact.kembali.create', ['purchaseId' => $purchaseId, 'materials' => $materials, 'gCompactRequest' => $gCompactRequest, 'gCompactRequestDetail' => $gCompactRequestDetail]);
    }

    public function Rstore(Request $request){

        // dd($request);

        $stokOpnameId = GudangStokOpname::CheckStokOpnameData($request);
                
        for ($i=0; $i < count($stokOpnameId); $i++) { 
            $request['gudangStokId'] = "$stokOpnameId[$i]";
            $request['gudangRequest'] = "Gudang Compact";
            $pengembalian = GudangMasuk::createBarangKembali($request); 
        }       
        if ($pengembalian) {
            $gudangMasukId = $pengembalian;
            $detailPengembalian = GudangKeluarDetail::where('gudangKeluarId', $request['id'])->get();        

            for ($i=0; $i < count($detailPengembalian); $i++) { 
                GudangMasukDetail::createBarangKembaliDetail($gudangMasukId, $request['gudangStokId'], $detailPengembalian[$i]);
            }

            return redirect('gudangCompact/Kembali');
        }
    }
    /* END Gudang Compact Request */

    /* Gudang Compact Kembali */
    public function gudangCompactKembali(){
        $gCompactKembali = GudangMasuk::where('gudangRequest', 'Gudang Compact')->get();
        return view('gudangCompact.kembali.index', ['gCompactKembali' => $gCompactKembali]);
    }

    public function KDetail($id){
        $gCompactKembali = GudangMasuk::where('gudangRequest', 'Gudang Compact')->where('id', $id)->first();
        $gCompactKembaliDetail = GudangMasukDetail::where('gudangMasukId', $id)->get();

        return view('gudangCompact.kembali.detail', ['gudangMasuk' => $gCompactKembali, 'gudangMasukDetail' => $gCompactKembaliDetail]);
    }

    /* END Gudang Compact Kembali */
}
