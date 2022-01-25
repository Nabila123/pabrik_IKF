<?php

namespace App\Http\Controllers;
use App\Models\GudangKeluar;
use App\Models\GudangMasuk;


use Illuminate\Http\Request;

class GudangCuciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->get();
        $gudangMasuk = GudangMasuk::where('gudangRequest', 'Gudang Cuci')->get();

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

    public function RTerimaBarang($id){

        $id = $id;  
        $gudangRequest = 'Gudang Cuci';  

        $gudangCuciTerima = GudangKeluar::updateStatusDiterima($id, $gudangRequest);

        if ($gudangCuciTerima == 1) {
            return redirect('gudangCuci/Request');
        }
    }

    public function Rcreate($id){
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $id)->first();

        return view('gudangCuci.kembali.create', ['gCuciRequest' => $gCuciRequest]);
    }

    public function Rstore(Request $request){
        $gCuciRequest = GudangKeluar::where('gudangRequest', 'Gudang Cuci')->where('id', $request['id'])->first();

        $pengembalian = GudangMasuk::createBarangKembali($gCuciRequest);        
        if ($pengembalian == 1) {
            return redirect('gudangCuci/Kembali');
        }
    }
    /* END Gudang Cuci Request */

    /* Gudang Cuci Kembali */
    public function gudangCuciKembali(){
        $gCuciKembali = GudangMasuk::where('gudangRequest', 'Gudang Cuci')->get();
        return view('gudangCuci.kembali.index', ['gCuciKembali' => $gCuciKembali]);
    }

    /* END Gudang Cuci Request */

}
