<?php

namespace App\Http\Controllers;
use App\Models\GudangCuciKeluar;
use App\Models\GudangCuciKeluarDetail;
use App\Models\GudangCompactKeluar;
use App\Models\GudangCompactKeluarDetail;
use App\Models\GudangCompactMasuk;
use App\Models\MaterialModel;
use Illuminate\Database\Eloquent\Builder;
use DB;

use Illuminate\Http\Request;

class GudangCuciController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gudangKeluar = GudangCuciKeluar::all();
        $gudangMasuk = GudangCuciKeluar::where('statusDiterima', '=', 1)->get();

        $gudangKeluar = count($gudangKeluar);
        $gudangMasuk = count($gudangMasuk);

        return view('gudangCuci.index', ['keluar' => $gudangKeluar, 'masuk' => $gudangMasuk]);
    }

    /* Gudang Cuci Request */
    public function gudangCuciRequest()
    {
        $gCuciRequest = GudangCuciKeluar::all();
        $total = 0;
        foreach ($gCuciRequest as $request) {
            $gCuciDetail = GudangCuciKeluarDetail::where('gdCuciKId', $request->id)->get();
            foreach ($gCuciDetail as $value) {
                $total += $value->qty;
            }

            $cekBahanPembantu = GudangCuciKeluarDetail::where('gdCuciKId', $request->id)
                ->whereHas('material', function (Builder $query) {
                    $query->where('keterangan', 'LIKE', '%Bahan Bantu / Merchandise%');
                })
                ->get();
            if (count($cekBahanPembantu) == 0) {
                $cekPengembalian = GudangCompactKeluar::where('gdCuciKId', $request->id)->first();
                if ($cekPengembalian != null && $total == 0) {
                    $request->cekPengembalian = 1;
                }
            } else {
                $request->cekPengembalian = 2;
            }
        }

        return view('gudangCuci.request.index', ['gCuciRequest' => $gCuciRequest]);
    }

    public function RDetail($id)
    {
        $gCuciRequest = GudangCuciKeluar::where('id', $id)->first();
        $gCuciRequestDetail = GudangCuciKeluarDetail::where('gdCuciKId', $id)->get();
        $cekBahanPembantu = GudangCuciKeluarDetail::where('gdCuciKId', $id)
            ->whereHas('material', function (Builder $query) {
                $query->where('keterangan', 'LIKE', '%Bahan Bantu / Merchandise%');
            })
            ->get();

        return view('gudangCuci.request.detail', ['gudangKeluar' => $gCuciRequest, 'gudangKeluarDetail' => $gCuciRequestDetail, 'cekBahanPembantu' => $cekBahanPembantu]);
    }

    public function RTerimaBarang($id)
    {
        $id = $id;
        $statusDiterima = 1;

        $gudangCuciTerima = GudangCuciKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangCuciTerima == 1) {
            return redirect('gudangCuci/Request');
        }
    }

    public function Rcreate($id)
    {
        $materials = MaterialModel::get();
        $gCuciRequest = GudangCuciKeluar::where('id', $id)->first();
        $gCuciRequestDetail = GudangCuciKeluarDetail::where('gdCuciKId', $id)->get();

        return view('gudangCuci.kembali.create', ['materials' => $materials, 'gCuciRequest' => $gCuciRequest, 'gCuciRequestDetail' => $gCuciRequestDetail]);
    }

    public function Rstore(Request $request)
    {
        $gdCompactKeluar = GudangCompactKeluar::CreateCompactKeluar($request->id);
        if ($gdCompactKeluar) {
            for ($i = 1; $i <= count($request->detailId); $i++) {
                if ($request['qty'][$i] == 1) {
                    $total = 0;
                    $gdCuciKeluar = GudangCuciKeluarDetail::where('id', $request['detailId'][$i])->first();
                    GudangCompactKeluarDetail::CreateCompactKeluarDetail($gdCuciKeluar->gudangId, $gdCuciKeluar->gdDetailMaterialId, $gdCompactKeluar, $gdCuciKeluar->purchaseId, $gdCuciKeluar->materialId, $gdCuciKeluar->jenisId, $gdCuciKeluar->gramasi, $gdCuciKeluar->diameter, $gdCuciKeluar->berat, $request['qty'][$i]);

                    $total = $request['qtyOri'][$i] - $request['qty'][$i];
                    GudangCuciKeluarDetail::gudangCuciUpdateField('qty', $total, $gdCuciKeluar->id);
                }
            }

            return redirect('gudangCuci/Kembali');
        }
    }
    /* END Gudang Cuci Request */

    /* Gudang Cuci Pemindahan */
    public function gudangCuciKembali()
    {
        $gCuciKembali = GudangCuciKeluar::all();
        return view('gudangCuci.kembali.index', ['gCuciKembali' => $gCuciKembali]);
    }

    public function KDetail($id)
    {
        $gCuciRequest = GudangCompactKeluar::where('gdCuciKId', $id)->first();
        $gCuciRequestDetail = GudangCompactKeluarDetail::where('gdCompactKId', $gCuciRequest->id)->get();

        return view('gudangCuci.kembali.detail', ['gudangKeluar' => $gCuciRequest, 'gudangKeluarDetail' => $gCuciRequestDetail]);
    }

    public function KDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $gdCompactKeluar = GudangCompactKeluar::where('gdCuciKId', $request->gdCuciKId)->first();
            $gdCompactKeluarDetail = GudangCompactKeluarDetail::where('gdCompactKId', $gdCompactKeluar->id)->get();
            foreach ($gdCompactKeluarDetail as $detail) {
                if ($detail->qty == 1) {
                    $getCuciDetail = GudangCuciKeluarDetail::where('gdDetailMaterialId', $detail->gdDetailMaterialId)->first();
                    $gdCuciKeluar = GudangCuciKeluarDetail::gudangCuciUpdateField('qty', 1, $getCuciDetail->id);
                }
            }

            if ($gdCuciKeluar) {
                $gdCompactKeluarDetail = GudangCompactKeluarDetail::where('gdCompactKId', $gdCompactKeluar->id)->delete();
                $gdCompactKeluar = GudangCompactKeluar::where('gdCuciKId', $request->gdCuciKId)->delete();
                if ($gdCompactKeluar) {
                    
                    DB::commit();
                    
                    return redirect()->back();
                }
            }
        } catch (\Throwable $th) {
            
            DB::rollback();
            dd($th);
        }
    }

    /* END Gudang Cuci Pemindahan */
}
