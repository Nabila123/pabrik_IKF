<?php

namespace App\Http\Controllers;
use App\Models\GudangKeluar;
use App\Models\GudangKeluarDetail;
use App\Models\GudangMasuk;
use App\Models\GudangMasukDetail;
use App\Models\GudangStokOpname;

use App\Models\MaterialModel;


use Illuminate\Http\Request;

class GudangCuciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->get();
        $gudangMasuk = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('statusDiterima', '>=', 2)->get();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangCuci.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Cuci Request */
    public function gudangCuciRequest(){
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->get();
        foreach ($gCuciRequest as $request) {
            $cekPengembalian = GudangMasuk::where('gudangRequest', 'Gudang Cuci')->where('gudangKeluarId', $request->id)->first();
            if ($cekPengembalian != null) {
                $request->cekPengembalian = 1;
            }
        }

        return view('gudangCuci.request.index', ['gCuciRequest' => $gCuciRequest]);
    }

    public function RDetail($id){
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $id)->first();
        $gCuciRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangCuci.request.detail', ['gudangKeluar' => $gCuciRequest, 'gudangKeluarDetail' => $gCuciRequestDetail]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $gudangRequest = 'Gudang Cuci';  
        $statusDiterima = 1;

        $gudangCuciTerima = GudangKeluar::updateStatusDiterima($id, $gudangRequest, $statusDiterima);

        if ($gudangCuciTerima == 1) {
            return redirect('gudangCuci/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::get();
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $id)->first();
        $gCuciRequestDetail = GudangKeluarDetail::where('gudangKeluarId', $id)->get();

        return view('gudangCuci.kembali.create', ['materials' => $materials, 'gCuciRequest' => $gCuciRequest, 'gCuciRequestDetail' => $gCuciRequestDetail]);
    }

    public function Rstore(Request $request){

        $gudangRequest = "Gudang Cuci";
        $statusDiterima = 2;

        $pemindahan = GudangKeluar::updateStatusDiterima($request->id, $gudangRequest, $statusDiterima);        
        if ($pemindahan == 1) {
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
