<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangControlStokOpname;
use App\Models\GudangControlMasuk;
use App\Models\GudangControlMasukDetail;
use App\Models\GudangControlRekap;
use App\Models\GudangControlRekapDetail;
use App\Models\Pegawai;

class GudangControlController extends Controller
{
    public function index()
    {
        $bajus = GudangControlStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $data = GudangControlStokOpname::where('statusControl', 0)->get();
        $dataStok=[];

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju]['qty'] = $dataStok[$value->jenisBaju]['qty'] + 1;
        }
        return view('gudangControl.index', ['dataStok' => $dataStok]);
    }
}
