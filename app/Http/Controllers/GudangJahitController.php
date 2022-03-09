<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangJahitMasuk;
use App\Models\GudangJahitMasukDetail;
use App\Models\GudangBajuStokOpname;
use App\Models\GudangJahitRequestOperator;
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

    public function getData(Request $request)
    {
        $data = [];
        $i = 0;
        $purchaseId = GudangBajuStokOpname::select('purchaseId')->where('jenisBaju', $request->jenisBaju)->groupBy('purchaseId')->get();

        foreach ($purchaseId as $detail) {
            if (!in_array($detail->purchaseId, $data)) {
                $data[$i]['purchaseId'] = $detail->purchaseId;
                $data[$i]['kode'] = $detail->purchase->kode;
                $i++;
            }
        }
        return json_encode($data);
    }

    //Gudang Request From Gudang Potong
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

    //Operator Request From Gudang Baju Stok Opname
    public function gOperator()
    {
        $gdRequestOperator = GudangJahitRequestOperator::groupBy('jenisBaju', 'ukuranBaju')->get();

        return view('gudangJahit.operator.index', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorDetail($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangJahitRequestOperator::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->get();

        return view('gudangJahit.operator.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorCreate()
    {
        $gdRequestOperator = GudangBajuStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();

        return view('gudangJahit.operator.create', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorStore(Request $request)
    {
        // dd($request['purchaseId']);
        $gdBajuStokOpnameId = [];
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $gdRequestOperator = GudangBajuStokOpname::where('purchaseId', $request['purchaseId'][$i])->where('jenisBaju', $request['jenisBaju'][$i])->where('ukuranBaju', $request['ukuranBaju'][$i])->where('soom', 0)->where('jahit', 0)->where('bawahan', 0)->get();
                foreach ($gdRequestOperator as $value) {
                   $gdBajuStokOpnameId[] = $value->id;
                }

                for ($j=0; $j < $request['jumlah'][$i]; $j++) { 
                    $createOperator = GudangJahitRequestOperator::OperatorBajuCreate($gdBajuStokOpnameId[$j], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i], 0, 0, 0, \Auth::user()->id);
                }
            }

            if ($createOperator) {
                return redirect('GJahit/operator');
            }
            // dd($gdBajuStokOpnameId);
        }else{
            $gdRequestOperator = GudangBajuStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
            return view('gudangJahit.operator.create', ['operatorRequest' => $gdRequestOperator, 'message'=>'Data Belum Diisi']);
        }
    }

    public function gOperatorDataMaterial($purchaseId, $jenisBaju, $ukuranBaju)
    {
        $data = [];
        if ($ukuranBaju == "null") {
            $gdRequestOperator = GudangBajuStokOpname::select('ukuranBaju')->where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->groupBy('ukuranBaju')->get();
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->ukuranBaju, $data)) {
                    $data[] = $operator->ukuranBaju;
                }
            }
        } else {
            $gdRequestOperator = GudangBajuStokOpname::where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('soom', 0)->where('jahit', 0)->where('bawahan', 0)->get();
            $data = count($gdRequestOperator);
            foreach ($gdRequestOperator as $value) {
                $cekOperator = GudangJahitRequestOperator::where('gdBajuStokOpnameId', $value->id)->first();
                if ($cekOperator != null) {
                   $data -= 1;
                }
            }
        }

        return json_encode($data);
        
    }

    //Gudang Jahit Reject From Gudang Batil & Control
    public function gReject()
    {
        return view('gudangJahit.reject.index');
    }
}
