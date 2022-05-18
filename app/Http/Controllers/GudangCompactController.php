<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangCompactKeluar;
use App\Models\GudangCompactKeluarDetail;
use App\Models\GudangCompactMasuk;
use App\Models\GudangCompactMasukDetail;
use App\Models\GudangBahanBaku;
use App\Models\GudangBahanBakuDetailMaterial;
use App\Models\MaterialModel;
use Illuminate\Database\Eloquent\Builder;


class GudangCompactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $gudangKeluar = GudangCompactKeluar::all();
        $gudangMasuk = GudangCompactMasuk::all();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangCompact.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }


    /* Gudang Compact Request */
    public function gudangCompactRequest(){
        $gCompactRequest = GudangCompactKeluar::all();
        foreach ($gCompactRequest as $request) {
            $cekBahanPembantu = GudangcompactKeluarDetail::where('gdCompactKId', $request->id)->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();
            if(count($cekBahanPembantu)== 0){
                $cekPengembalian = GudangCompactMasuk::where('gdCompactKId', $request->id)->first();
                if ($cekPengembalian != null) {
                    $request->cekPengembalian = 1;
                }
            }else{
                $request->cekPengembalian = 2;
            }
        }

        return view('gudangCompact.request.index', ['gCompactRequest' => $gCompactRequest]);
    }

    public function RDetail($id){
        $gCompactRequest = GudangCompactKeluar::where('id', $id)->first();
        $gCompactRequestDetail = GudangCompactKeluarDetail::where('gdCompactKId', $id)->get();
        $cekBahanPembantu = GudangCompactKeluarDetail::where('gdCompactKId', $id)->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();

        return view('gudangCompact.request.detail', ['gudangKeluar' => $gCompactRequest, 'gudangKeluarDetail' => $gCompactRequestDetail, 'cekBahanPembantu'=>$cekBahanPembantu]);
    }

    public function RTerimaBarang($id){

        $id = $id;  
        $statusDiterima = 1;  

        $gudangCompactTerima = GudangCompactKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangCompactTerima == 1) {
            return redirect('gudangCompact/Request');
        }
    }

    public function Rcreate($id){
        $materials = MaterialModel::where('id', 3)->first();
        $gCompactRequest = GudangCompactKeluar::where('id', $id)->first();
        $gCompactRequestDetail = GudangCompactKeluarDetail::where('gdCompactKId', $id)->get();

        foreach ($gCompactRequestDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }

        return view('gudangCompact.kembali.create', ['purchaseId' => $purchaseId, 'material' => $materials, 'gCompactRequest' => $gCompactRequest, 'gCompactRequestDetail' => $gCompactRequestDetail]);
    }

    public function Rstore(Request $request){
        // dd($request);
        $gCompactRequest = GudangCompactKeluar::where('id', $request->id)->first();
        $gCompactRequestDetail = GudangCompactKeluarDetail::where('gdCompactKId', $gCompactRequest->id)->get();
        // dd($gCompactRequestDetail);
        if ($gCompactRequestDetail != null) {
            $gdCompactMasuk = GudangCompactMasuk::CreateCompactMasuk($gCompactRequest->id);
            if ($gdCompactMasuk) {
               foreach ($gCompactRequestDetail as $detail) {
                   $gdBahanBaku = GudangBahanBaku::CheckBahanBakuForCompact($detail->gudangId, $detail->purchaseId, $request->materialId, $detail->diameter, $detail->gramasi, 0, $detail->berat, 0, 0, $request->satuan, 0, 0, null);
                   if ($gdBahanBaku) {
                    $gudangCompactDetail = GudangCompactMasukDetail::CreateCompactMasukDetail($detail->gudangId, $gdBahanBaku, $gdCompactMasuk, $detail->purchaseId, $request->materialId, $request->materialId, $detail->gramasi, $detail->diameter, $detail->berat, $detail->qty);
                   }
               }

               if ($gudangCompactDetail == 1) {
                    return redirect('gudangCompact/Kembali');
                }
            }
        }            
        
    }
    /* END Gudang Compact Request */

    /* Gudang Compact Kembali */
    public function gudangCompactKembali(){
        $gCompactKembali = GudangCompactMasuk::all();
        return view('gudangCompact.kembali.index', ['gCompactKembali' => $gCompactKembali]);
    }

    public function KDetail($id){
        $gCompactKembali = GudangCompactMasuk::where('id', $id)->first();
        $gCompactKembaliDetail = GudangCompactMasukDetail::where('gdCompactMId', $id)->get();

        return view('gudangCompact.kembali.detail', ['gudangMasuk' => $gCompactKembali, 'gudangMasukDetail' => $gCompactKembaliDetail]);
    }

    public function KDelete(Request $request)
    {
        $gdCompactMasuk = GudangCompactMasuk::where('id', $request->gdCompactMId)->first();
        $gdCompactMasukDetail = GudangCompactMasukDetail::where('gdCompactMId', $gdCompactMasuk->id)->get();
        $DeleteRajutKeluarDetail = GudangCompactMasukDetail::where('gdCompactMId', $gdCompactMasuk->id)->delete();
        if ($DeleteRajutKeluarDetail) {
            foreach ($gdCompactMasukDetail as $detail) {
                $gdDetailMaterial = GudangBahanBakuDetailMaterial::where('id', $detail->gdDetailMaterialId)->delete();
            }
    
            if ($gdDetailMaterial) {
                $gdCompactMasuk = GudangCompactMasuk::where('id', $request->gdCompactMId)->delete();
                if ($gdCompactMasuk) {
                    return redirect('gudangCompact/Request');
                }                
            }
        }
    }

    /* END Gudang Compact Kembali */
}
