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
use App\Models\GudangJahitRekap;
use App\Models\GudangJahitRekapDetail;
use App\Models\GudangJahitReject;
use App\Models\GudangJahitRejectDetail;
use App\Models\Pegawai;

use DB;
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


    public function getPegawai(Request $request)
    {
        // dd($request);
        $data = [];
        $pegawai = Pegawai::where('kodeBagian', $request->posisi)->get();
        foreach ($pegawai as $value) {
            $data['pegawai'][] = [
                'id' => $value->id, 
                'nip' => $value->nip, 
                'nama' => $value->nama
            ];
        } 

        if (isset($request->purchaseId)) {
            $gdRequestOperator = GudangJahitRequestOperator::where($request->posisi, 0)->where('purchaseId', $request->purchaseId)->whereDate('created_at', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->jenisBaju)) {
            $gdRequestOperator = GudangJahitRequestOperator::where($request->posisi, 0)->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->whereDate('created_at', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        if (isset($request->ukuranBaju)) {
            $reqOperatorId = [];
            $soom = $request->soom;
            $jahit = $request->jahit;
            $bawahan = $request->bawahan;           
            if ($request->posisi == "soom") {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) use ($jahit, $bawahan) {
                                                                    $d->where('soom', 0)
                                                                    ->where('jahit', $jahit)
                                                                    ->where('bawahan', $bawahan)
                                                                    ->orWhere(function ($b) use ($jahit) {
                                                                        $b->where('soom', 0)
                                                                        ->where('jahit', $jahit)
                                                                        ->where('bawahan', 0);
                                                                    })
                                                                    ->orWhere(function ($c) use ($bawahan) {
                                                                        $c->where('soom', 0)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', $bawahan);
                                                                    })
                                                                    ->orwhere(function ($a) {
                                                                        $a->where('soom', 0)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', 0);
                                                                    });
                                                                })    
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }elseif ($request->posisi == "jahit") {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) use ($soom, $bawahan) {
                                                                    $d->where('soom', $soom)
                                                                    ->where('jahit', 0)
                                                                    ->where('bawahan', $bawahan)
                                                                    ->orWhere(function ($b) use ($soom) {
                                                                        $b->where('soom', $soom)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', 0);
                                                                    })
                                                                    ->orWhere(function ($c) use ($bawahan) {
                                                                        $c->where('soom', 0)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', $bawahan);
                                                                    })
                                                                    ->orwhere(function ($a) {
                                                                        $a->where('soom', 0)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', 0);
                                                                    });
                                                                })                                                                
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }else {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) use ($soom, $jahit) {
                                                                    $d->where('soom', $soom)
                                                                    ->where('jahit', $jahit)
                                                                    ->where('bawahan', 0)
                                                                    ->orWhere(function ($b) use ($soom) {
                                                                        $b->where('soom', $soom)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', 0);
                                                                    })
                                                                    ->orWhere(function ($c) use ($jahit) {
                                                                        $c->where('soom', 0)
                                                                        ->where('jahit', $jahit)
                                                                        ->where('bawahan', 0);
                                                                    })
                                                                    ->orwhere(function ($a) {
                                                                        $a->where('soom', 0)
                                                                        ->where('jahit', 0)
                                                                        ->where('bawahan', 0);
                                                                    });
                                                                })                                                                
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }

            if (!isset($request->jumlahBaju)) {
                $request->jumlahBaju = count($gdRequestOperator);
            } 
            $i = 0;
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->id, $reqOperatorId) && $i < $request->jumlahBaju) {
                    $reqOperatorId[$i] = $operator->id;
                    $i++;
                }
            }            
            
            // dd($gdRequestOperator);
        }
        if (!isset($request->purchaseId) && !isset($request->jenisBaju) && !isset($request->ukuranBaju)){
            $gdRequestOperator = GudangJahitRequestOperator::where($request->posisi, 0)->whereDate('created_at', date('Y-m-d'))->groupBy($request->groupBy)->get();
        }
        

        if (isset($gdRequestOperator)) {
            foreach ($gdRequestOperator as $operator) {
                if ($request->groupBy == "purchaseId") {
                    $data['operator'][] = [
                        'purchaseId' => $operator->purchaseId, 
                        'kodePurchase' => $operator->purchase->kode
                    ];
                }elseif ($request->groupBy == "jenisBaju") {
                    $data['operator'][] = [
                        'jenisBaju' => $operator->jenisBaju
                    ];
                }elseif ($request->groupBy == "ukuranBaju") {
                    $data['operator'][] = [
                        'ukuranBaju' => $operator->ukuranBaju
                    ];
                }
            }

            if ($request->groupBy == "id"){
                $data['operator'] = [
                    'requestOperatorId' => $reqOperatorId,
                    'jumlahBaju' => $request->jumlahBaju
                ];
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
        $gdRequestOperator = GudangJahitRequestOperator::groupBy('jenisBaju', 'ukuranBaju')->whereDate('created_at', date('Y-m-d'))->get();
        $gdJahitBasis = GudangJahitBasis::groupBy('posisi')->whereDate('created_at', date('Y-m-d'))->get();
        $gdJahitRekap = GudangJahitRekap::orderBy('tanggal', 'DESC')->groupBy('posisi', 'tanggal')->get();
        $dataPemindahan = GudangJahitRequestOperator::select('*', DB::raw('count(*) as jumlah'))->groupBy('jenisBaju', 'ukuranBaju')->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->get();

        return view('gudangJahit.operator.index', ['operatorRequest' => $gdRequestOperator, 'jahitBasis' => $gdJahitBasis, 'jahitRekap' => $gdJahitRekap, 'dataPemindahan' => $dataPemindahan]);
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

    public function gOperatorUpdate($date)
    {
        $jenisBaju = GudangBajuStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();
        $gdRequestOperator = GudangJahitRequestOperator::selectRaw('* ,count(*) as total')->whereDate('created_at', $date)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        $gdJahitBasis = GudangJahitBasis::whereDate('created_at', date('Y-m-d'))->first();

        return view('gudangJahit.operator.update', ['gdRequestOperator' => $gdRequestOperator, 'operatorRequest' => $jenisBaju, 'gdJahitBasis' => $gdJahitBasis]);
    }

    public function gOperatorUpdateSave(Request $request)
    {
        // dd($request);
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

    public function gOperatorUpdateDelete($purchaseId, $jenisBaju, $ukuranBaju)
    {
        $gdRequestOperatorDelete = GudangJahitRequestOperator::where('purchaseId', $purchaseId)->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->whereDate('created_at', date('Y-m-d'))->delete();
        if ($gdRequestOperatorDelete) {
            return redirect('GJahit/operator/update/' . date('Y-m-d') . '');
        }
    }

    public function gOperatorDelete(Request $request)
    {
        $gdRequestOperatorDelete = GudangJahitRequestOperator::where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->whereDate('created_at', date('Y-m-d'))->delete();
        if ($gdRequestOperatorDelete) {
            return redirect('GJahit/operator');
        }

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

    public function gBasisUpdate($posisi)
    {
    //    dd($posisi);
       $gdBasisDetail= [];
       $i = 0;
       $gdJahitBasis = GudangJahitBasis::where('posisi', $posisi)->whereDate('created_at', date('Y-m-d'))->first();
       $gdJahitPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdJahitBasis->id)->whereDate('created_at', date('Y-m-d'))->get();
       foreach ($gdJahitPegawai as $pegawai) {
           $j = 0; $k = 0;
            $gdBasisDetail[$i] = [
                'posisiJahit' => $gdJahitBasis->id,
                'nama' => $pegawai->pegawai->nama,
                'basisId' => $pegawai->id,
                'jam' => [],
                'total' => []
            ];
            $gdJahitPegawaiDetail = GudangJahitDetailMaterial::where('gdJahitBasisPegawaiId', $pegawai->id)->orderBy('created_at', 'asc')->get();
            foreach ($gdJahitPegawaiDetail as $detail) {
                $gdBasisDetail[$i]['jam'][$k++] = date("H:i", strtotime($detail->created_at));
                $gdBasisDetail[$i]['total'][$j]['detailId'] = $detail->id;
                $gdBasisDetail[$i]['total'][$j]['total'] = $detail->total;
                $j++;
            }
        $i++;
       }

    //    dd($gdBasisDetail);
       return view('gudangJahit.basis.update', ['gdBasisDetail' => $gdBasisDetail, 'gdJahitBasis' => $gdJahitBasis]);

    }

    public function gBasisUpdateSave(Request $request)
    {
        // dd($request);
        for ($i=0; $i < count($request->basisId); $i++) { 
            $gdJahitPegawaiDetail = GudangJahitDetailMaterial::GudangBasisPegawaiDetailUpdateField('total', $request['total'][$i], $request['detailMaterialId'][$i]);
            if ($gdJahitPegawaiDetail) {
                $gdBasisPegawai = GudangJahitDetail::GudangBasisPegawaiUpdateField('total', $request['total'][$i], $request['basisId'][$i]);
            }
        }

        if ($gdBasisPegawai) {
            $gdBasis = GudangJahitBasis::GudangBasisUpdateField('total', array_sum($request['total']), $request->posisiId);
            if ($gdBasis) {
                return redirect('GJahit/operator');
            }
        }

    }

    public function gBasisDelete(Request $request)
    {
        // dd($request);
        $gdBasis = GudangJahitBasis::where('posisi', $request->jenisBaju)->whereDate('created_at', date('Y-m-d'))->first();
        $gdBasisPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdBasis->id)->get();
        foreach ($gdBasisPegawai as $pegawai) {
           $gdBasisPegawaiDetail = GudangJahitDetailMaterial::where('gdJahitBasisPegawaiId', $pegawai->id)->delete();
        }

        if ($gdBasisPegawaiDetail) {
            $gdBasisPegawai = GudangJahitDetail::where('gdJahitBasisId', $gdBasis->id)->delete();
            if ($gdBasisPegawai) {
                $gdBasis = GudangJahitBasis::where('posisi', $request->jenisBaju)->whereDate('created_at', date('Y-m-d'))->delete();
                if ($gdBasis) {
                    return redirect('GJahit/operator');
                }
            }
        }
        
    }


    public function gRekapCreate()
    {
        return view('gudangJahit.rekap.create');
    }

    public function gRekapStore(Request $request)
    {
        // dd($request);        
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $checkPegawai = GudangJahitRekap::where('posisi', $request['posisi'][$i])->where('tanggal', date('Y-m-d'))->first();
                if ($checkPegawai == null) {
                    $jahitRekap = GudangJahitRekap::JahitRekapCreate($request['posisi'][$i], date('Y-m-d'), \Auth::user()->id);
                } else {
                    $jahitRekap = $checkPegawai->id;
                }

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangJahitRequestOperator::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $operatorReqUpdate = GudangJahitRequestOperator::GudangOperatorBajuUpdateField($request['posisi'][$i], 1, $operatorReq->id);
                        if ($operatorReqUpdate == 1) {
                            $bajuStokOpname = GudangBajuStokOpname::bajuUpdateField($request['posisi'][$i], 1, $operatorReq->gdBajuStokOpnameId);
                            if ($bajuStokOpname == 1) {
                                $jahitRekapDetail = GudangJahitRekapDetail::JahitRekapDetailCreate($jahitRekap, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                            }
                        }                    
                    } 
                }                                             
            }

            if ($jahitRekapDetail == 1) {
                return redirect('GJahit/operator');
            }
        } else {
            return redirect('GJahit/rekap/create');
        }
        
    }

    public function gRekapDetail($id)
    {
        $getPegawai = GudangJahitRekap::where('id', $id)->first();
        $getDetailPegawai = GudangJahitRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdJahitRekapId', $getPegawai->id)->groupBy('pegawaiId', 'tanggal', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        foreach ($getDetailPegawai as $detailPegawai) {
            $detailPegawai->posisi = $getPegawai->posisi;        
        }

        return view('gudangJahit.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id) 
    {
        $getPegawai = GudangJahitRekap::where('id', $id)->whereDate('created_at', date('Y-m-d'))->first();
        $getDetailPegawai = GudangJahitRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdJahitRekapId', $getPegawai->id)->groupBy('pegawaiId', 'tanggal', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        foreach ($getDetailPegawai as $detailPegawai) {
            $detailPegawai->posisi = $getPegawai->posisi;
            $detailPegawai->soom = $detailPegawai->operatorReq->soom;
            $detailPegawai->jahit = $detailPegawai->operatorReq->jahit;
            $detailPegawai->bawahan = $detailPegawai->operatorReq->bawahan;
        }

        // dd($getDetailPegawai);

        return view('gudangJahit.rekap.update', ['pegawai' => $getDetailPegawai, 'posisi' => $getPegawai->posisi, 'id' => $getPegawai->id]);
    }

    public function gRekapUpdateSave(Request $request)
    {
        // dd($request);
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $jahitRekap = GudangJahitRekap::where('id', $request->id)->first();               

                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                for($j=0; $j < count($operatorReqId); $j++){
                    $operatorReq = GudangJahitRequestOperator::where('id', $operatorReqId[$j])->first();
                    if ($operatorReq != null) {
                        $operatorReqUpdate = GudangJahitRequestOperator::GudangOperatorBajuUpdateField($request['posisi'][$i], 1, $operatorReq->id);
                        if ($operatorReqUpdate == 1) {
                            $bajuStokOpname = GudangBajuStokOpname::bajuUpdateField($request['posisi'][$i], 1, $operatorReq->gdBajuStokOpnameId);
                            if ($bajuStokOpname == 1) {
                                $jahitRekapDetail = GudangJahitRekapDetail::JahitRekapDetailCreate($jahitRekap->id, $request['pegawaiId'][$i], $operatorReq->gdBajuStokOpnameId, $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i]);
                            }
                        }                    
                    } 
                }                                             
            }

            if ($jahitRekapDetail == 1) {
                return redirect('GJahit/operator');
            }
        } else {
            return redirect('GJahit/rekap/create');
        }
    }

    public function gRekapUpdateDelete($rekapId, $rekapDetailId, $posisi)
    {
        $getDetailPegawai = GudangJahitRekapDetail::where('id', $rekapDetailId)->first();
        $rekapId = $getDetailPegawai->gdJahitRekapId;
        $getPegawai = GudangJahitRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangJahitRekapDetail::where('gdJahitRekapId', $getPegawai->id)->get();

        $operatorReq = GudangJahitRequestOperator::where('gdBajuStokOpnameId', $getDetailPegawai->gdBajuStokOpnameId)->first();
        if ($operatorReq != null) {
            $operatorReqUpdate = GudangJahitRequestOperator::GudangOperatorBajuUpdateField($posisi, 0, $operatorReq->id);
            if ($operatorReqUpdate == 1) {
                $bajuStokOpname = GudangBajuStokOpname::bajuUpdateField($posisi, 0, $operatorReq->gdBajuStokOpnameId);
                if ($bajuStokOpname == 1) {
                    $deleteDetailPegawai = GudangJahitRekapDetail::where('id', $rekapDetailId)->delete();
                    if (count($CheckPegawai) == 1) {
                        $deletePegawai = GudangJahitRekap::where('id', $rekapId)->delete();

                        if ($deletePegawai) {
                            return redirect('GJahit/operator');
                        }
                    }

                    if ($deleteDetailPegawai) {
                        return redirect('GJahit/rekap/update/' . $rekapId . '');
                    }
                }
            }                    
        }            
    }


    public function gKeluarCreate()
    {
        $dataPemindahan = GudangJahitRequestOperator::select('*', DB::raw('count(*) as jumlah'))->groupBy('purchaseId','jenisBaju', 'ukuranBaju')->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->get();

        return view('gudangJahit.keluar.create', ['pemindahan' => $dataPemindahan]);
    }

    public function gKeluarDetail($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangJahitRequestOperator::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $operator) {
            $gdRekapDetail = GudangJahitRekapDetail::where('gdBajuStokOpnameId', $operator->gdBajuStokOpnameId)->get();
            foreach ($gdRekapDetail as $detail) {
                if ($detail->rekap->posisi == "Soom") {
                   $operator->soomName = $detail->pegawai->nama;
                }
                if ($detail->rekap->posisi == "Jahit") {
                    $operator->jahitName = $detail->pegawai->nama;

                }
                if ($detail->rekap->posisi == "Bawahan") {
                    $operator->bawahanName = $detail->pegawai->nama;

                }
            }
        }

        // dd($gdRequestOperator);
        return view('gudangJahit.keluar.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    //Gudang Jahit Reject From Gudang Batil & Control
    public function gReject()
    {
        $gdJahitReject = GudangJahitReject::all();

        return view('gudangJahit.reject.index', ['jahitReject' => $gdJahitReject]);
    }

    public function gRejectTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangPotongTerima = GudangJahitReject::updateStatusDiterima($id, $statusDiterima);

        if ($gudangPotongTerima == 1) {
            return redirect('GJahit/reject');
        }
    }

    public function gRejectDetail($id)
    {
        $gdJahitReject = GudangJahitReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdJahitReject->id)->get();

        return view('gudangJahit.reject.detail', ['jahitRejectDetail' => $gdJahitRejectDetail]);
    }
}
