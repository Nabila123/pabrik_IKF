<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangBatilStokOpname;


class GudangBatilController extends Controller
{
    public function index()
    {
        $bajus = GudangBatilStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangBatilStokOpname::where('statusBatil', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        return view('gudangBatil.index', ['dataStok' => $dataStok]);
    }

    public function gRequest()
    {
        return view('gudangBatil.request.index');
    }

    public function gOperator()
    {
        return view('gudangBatil.operator.index');
    }

    public function gReject()
    {
        return view('gudangBatil.reject.index');
    }
}
