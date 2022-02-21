<?php

namespace App\Http\Controllers;
use App\Models\GudangCuciKeluar;
use App\Models\GudangCuciKeluarDetail;
use App\Models\GudangCompactKeluar;
use App\Models\GudangCompactKeluarDetail;

use App\Models\MaterialModel;


use Illuminate\Http\Request;

class GudangCuciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangCuciKeluar::all();
        $gudangMasuk = GudangCuciKeluar::where('statusDiterima', '=', 1)->get();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangCuci.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Cuci Request */
    public function gudangCuciRequest(){
        $gCuciRequest = GudangCuciKeluar::all();
        $total = 0;
        foreach ($gCuciRequest as $request) {
            $gCuciDetail = GudangCuciKeluarDetail::where('gdCuciKId', $request->id)->get();
            foreach ($gCuciDetail as $value) {
                $total += $value->qty;
            }

            $cekPengembalian = GudangCompactKeluar::where('gdCuciKId', $request->id)->first();
            if ($cekPengembalian != null && $total == 0) {
                $request->cekPengembalian = 1;
            }
        }

        return view('gudangCuci.request.index', ['gCuciRequest' => $gCuciRequest]);
    }

    public function RDetail($id){
        $gCuciRequest = GudangCuciKeluar::where('id', $id)->first();
        $gCuciRequestDetail = GudangCuciKeluarDetail::where('gdCuciKId', $id)->get();

        return view('gudangCuci.request.detail', ['gudangKeluar' => $gCuciRequest, 'gudangKeluarDetail' => $gCuciRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;   
        $statusDiterima = 1;

        $gudangCuciTerima = GudangCuciKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangCuciTerima == 1) {
            return redirect('gudangCuci/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gCuciRequest = GudangCuciKeluar::where('id', $id)->first();
        $gCuciRequestDetail = GudangCuciKeluarDetail::where('gdCuciKId', $id)->get();

        return view('gudangCuci.kembali.create', ['materials' => $materials, 'gCuciRequest' => $gCuciRequest, 'gCuciRequestDetail' => $gCuciRequestDetail]);
    }

    public function Rstore(Request $request){
        $gdCompactKeluar = GudangCompactKeluar::CreateCompactKeluar($request->id); 
        if ($gdCompactKeluar) {
            for ($i=1; $i <= count($request->detailId); $i++) { 
                $total = 0;
                $gdCuciKeluar = GudangCuciKeluarDetail::where('id', $request['detailId'][$i])->first();
                GudangCompactKeluarDetail::CreateCompactKeluarDetail($gdCuciKeluar->gudangId, $gdCompactKeluar, $gdCuciKeluar->purchaseId, $gdCuciKeluar->materialId, $gdCuciKeluar->jenisId, $gdCuciKeluar->gramasi, $gdCuciKeluar->diameter, $gdCuciKeluar->berat, $request["qty"][$i]);
                
                $total = $request["qtyOri"][$i] - $request["qty"][$i];
                GudangCuciKeluarDetail::gudangCuciUpdateField("qty", $total, $gdCuciKeluar->id);
            }

            return redirect('gudangCuci/Kembali');

        }       
    }
    /* END Gudang Cuci Request */

    /* Gudang Cuci Pemindahan */
    public function gudangCuciKembali(){
        $gCuciKembali = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('statusDiterima', '>', 1)->get();
        return view('gudangCuci.kembali.index', ['gCuciKembali' => $gCuciKembali]);
    }

    public function KDetail($id){
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $id)->first();
        $gCuciRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangCuci.kembali.detail', ['gudangKeluar' => $gCuciRequest, 'gudangKeluarDetail' => $gCuciRequestDetail]);
    }

    /* END Gudang Cuci Pemindahan */

}
