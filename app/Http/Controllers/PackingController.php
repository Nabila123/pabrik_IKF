<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangSetrikaStokOpname;
use App\Models\GudangPackingRekap;
use App\Models\GudangPackingRekapDetail;
use App\Models\GudangControlReject;
use App\Models\GudangControlRejectDetail;


class PackingController extends Controller
{
    public function index()
    {
        $bajus = GudangSetrikaStokOpname::select('jenisBaju')->where('statusSetrika', 1)->groupBy('jenisBaju')->get();
        $data = GudangSetrikaStokOpname::where('statusSetrika', 1)->where('statusPacking', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }

        return view('packing.index', ['dataStok' => $dataStok]);
    }

    public function gOperator()
    {
        $gdPackingRekap = GudangPackingRekap::where('tanggal', date('Y-m-d'))->get();

        return view('packing.operator.index', ['packingRekap' => $gdPackingRekap]);
    }

    public function gReject()
    {
        return view('packing.reject.index');
    }
}
