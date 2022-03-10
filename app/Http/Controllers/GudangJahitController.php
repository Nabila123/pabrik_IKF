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
use App\Models\Pegawai;

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

    public function getBasis(Request $request)
    {
        $data = [];
        $pegawai = Pegawai::where('kodeBagian', $request->posisi)->get();
        foreach ($pegawai as $value) {
            $data['pegawai'][] = [
                'id' => $value->id, 
                'nip' => $value->nip, 
                'nama' => $value->nama
            ];
        } 

        $gdBasis = GudangJahitBasis::where('posisi', $request->posisi)->first();
        if ($gdBasis != null) {
            $gdBasisPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdBasis->id)->get();
            foreach ($gdBasisPegawai as $pegawai) {
                $data['jumlah'] = [
                    'target' => $gdBasis->qtyTarget, 
                    'total' => $gdBasis->total
                ];
                $data['basis']['pegawai'][] = $pegawai->pegawai->nama;
                $data['basis']['jumlah'][] = $pegawai->total;
                
            }
        }else{
            $data['jumlah'] = [
                'target' => 0, 
                'total' => 0
            ];
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
        $gdRequestOperator = GudangJahitRequestOperator::groupBy('jenisBaju', 'ukuranBaju')->whereDate('created_at', date('Y-m-d'))->get();
        $gdJahitBasis = GudangJahitBasis::groupBy('posisi')->whereDate('created_at', date('Y-m-d'))->get();

        return view('gudangJahit.operator.index', ['operatorRequest' => $gdRequestOperator, 'jahitBasis' => $gdJahitBasis]);
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

    //Gudang Jahit Basis (Proses Jahit)
    public function gBasisCreate()
    {
        return view('gudangJahit.basis.create');
    }

    public function gBasisDetail($posisi)
    {
        $gdJahitBasis = GudangJahitBasis::where('posisi', $posisi)->whereDate('created_at', date('Y-m-d'))->first();
        $gdJahitPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdJahitBasis->id)->get();

        return view('gudangJahit.basis.detail', ['basis' => $gdJahitBasis, 'basisPegawai' => $gdJahitPegawai]);
    }

    public function gBasisStore(Request $request)
    {
        // dd($request);
        $gdBasisCheck = GudangJahitBasis::where('posisi', $request->posisi)->whereDate('created_at', date('Y-m-d'))->first();
        if ($request->posisi != "" && $request->qtyTarget != "") {
            if ($gdBasisCheck == null) {
                $gdBasis = GudangJahitBasis::CreateGudangBasis($request->posisi, $request->qtyTarget*12, array_sum($request->jumlah), \Auth::user()->id);
                if ($gdBasis) {
                    for ($i=0; $i < count($request['pegawaiId']); $i++) { 
                        // dd($request['pegawaiId']);
                        $gdBasisPegawai = GudangJahitDetail::CreateGudangBasisPegawai($gdBasis, $request['pegawaiId'][$i], $request['jumlah'][$i]);
                        if ($gdBasisPegawai) {
                            $gdBasisDetail = GudangJahitDetailMaterial::CreateGudangBasisDetail($gdBasisPegawai, $request['jumlah'][$i]);
                        }
                    }
                    if ($gdBasisDetail == 1) {
                        return redirect('GJahit/operator');
                    }                
                }
            } else {
                $gdBasis = GudangJahitBasis::GudangBasisUpdateField('total', array_sum($request->jumlah), $gdBasisCheck->id);
                $gdJahitPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdBasisCheck->id)->get();
                foreach ($gdJahitPegawai as $detail) {
                    for ($i=0; $i < count($request['pegawaiId']); $i++) { 
                        if ($detail->pegawaiId == $request['pegawaiId'][$i]) {
                            $gdBasisPegawai = GudangJahitDetail::GudangBasisPegawaiUpdateField('total', $request['jumlah'][$i], $detail->id);
                            if ($gdBasisPegawai == 1) {
                                $gdBasisDetail = GudangJahitDetailMaterial::CreateGudangBasisDetail($detail->id, $request['jumlah'][$i]);
                            }
                        }
                    }
                }
                if ($gdBasisDetail == 1) {
                    return redirect('GJahit/operator');
                }  
            }
            
        }
    }

    //Gudang Jahit Reject From Gudang Batil & Control
    public function gReject()
    {
        return view('gudangJahit.reject.index');
    }
}
