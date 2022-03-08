<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangJahitMasuk;
use App\Models\GudangJahitMasukDetail;
use App\Models\GudangBajuStokOpname;
use App\Models\GudangJahitBasis;
use App\Models\GudangJahitDetail;
use App\Models\GudangJahitDetailMaterial;

class GudangJahitController extends Controller
{
    public function index()
    {
        $bajus = GudangBajuStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangBajuStokOpname::where('soom', 0)->where('jahit', 0)->where('bawahan', 0)->get();
        $belumSelesai = GudangBajuStokOpname::where(function ($a) {
                                                $a->where('soom', 1)
                                                  ->where('jahit', 0)
                                                  ->where('bawahan', 0);
                                            })->orWhere(function ($b) {
                                                $b->where('soom', 0)
                                                  ->where('jahit', 1)
                                                  ->where('bawahan', 0);
                                            })->orWhere(function ($c) {
                                                $c->where('soom', 0)
                                                  ->where('jahit', 0)
                                                  ->where('bawahan', 1);
                                            })->orWhere(function ($a) {
                                                $a->where('soom', 1)
                                                  ->where('jahit', 1)
                                                  ->where('bawahan', 0);
                                            })->orWhere(function ($b) {
                                                $b->where('soom', 1)
                                                  ->where('jahit', 0)
                                                  ->where('bawahan', 1);
                                            })->orWhere(function ($c) {
                                                $c->where('soom', 0)
                                                  ->where('jahit', 1)
                                                  ->where('bawahan', 1);
                                            })
                                            ->get();
                                            
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        
        return view('gudangJahit.index', ['dataStok' => $dataStok, 'dataProses' => $belumSelesai]);
    }

    public function gRequest()
    {
        $gudangJahit = GudangJahitMasuk::all();

        return view('gudangJahit.request.index', ['gudangJahit' => $gudangJahit]);
    }

    public function gRequestTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangJahit = GudangJahitMasuk::where('id',$id)->first();
        $gudangJahitDetail = GudangJahitMasukDetail::where('gdJahitMId', $gudangJahit->id)->get();

        $gudangJahitTerima = GudangJahitMasuk::updateStatusDiterima($id, $statusDiterima);

        if ($gudangJahitTerima == 1) {
            foreach ($gudangJahitDetail as $value) {
                $gdBajuStokOpname = GudangBajuStokOpname::BajuCreate($value->gdPotongProsesId, $value->purchaseId, $value->jenisBaju, $value->ukuranBaju, 0, 0, 0, $value->qty, \Auth::user()->id);
            }

            if ($gdBajuStokOpname == 1) {
                return redirect('GJahit/request');
            }
        }
    }

    public function gRequestDetail($id)
    {
        $gudangJahit = GudangJahitMasuk::where('id',$id)->first();
        $gudangJahitDetail = GudangJahitMasukDetail::where('gdJahitMId', $gudangJahit->id)->get();

        return view('gudangJahit.request.detail', ['gdJahit' => $gudangJahit, 'gdJahitDetail' => $gudangJahitDetail]);

    }

    public function gOperator()
    {
        return view('gudangJahit.operator.index');
    }

    public function gReject()
    {
        return view('gudangJahit.reject.index');
    }
}
