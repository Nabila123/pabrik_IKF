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
use App\Models\GudangBatilMasuk;
use App\Models\GudangBatilMasukDetail;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Builder;

use DB;
class GudangJahitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $bajus = GudangBajuStokOpname::select('jenisBaju', 'ukuranBaju')->groupBy('jenisBaju', 'ukuranBaju')->get();
        $data = GudangBajuStokOpname::where('soom', 0)->where('jahit', 0)->where('bawahan', 0)->get();
        $belumSelesai = GudangBajuStokOpname::select('*', DB::raw('count(*) as jumlah'))->where(function ($a) {
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
                                            ->groupBy('purchaseId','jenisBaju', 'ukuranBaju')
                                            ->get();
                                            
        $dataStok=[];

        foreach ($belumSelesai as $detail) {
            $detail->totalDz =  (int)($detail->jumlah/12);
            $sisa = ($detail->jumlah%12);
            if ($sisa != 0) {
                $detail->sisa = $sisa;
            }  
        }

        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['ukuran'] = $baju->ukuranBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] = $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] + 1;
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

        $gdBasis = GudangJahitBasis::where('posisi', $request->posisi)->whereDate('created_at', date('Y-m-d'))->first();
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
            // dd($request);
            $reqOperatorId = [];
            $soom = $request->soom;
            $jahit = $request->jahit;
            $bawahan = $request->bawahan; 
            
            $checkId = [];   
            $index = 0;    
            $checkId[$index]['operatorReqId'] = [];
            $checkId[$index]['purchase'] = [];
            $checkId[$index]['jumlah'] = [];

            if ($request->posisi == "soom") {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) {
                                                                    $d->where('soom', 0)
                                                                    ->where('jahit', 0)
                                                                    ->where('bawahan', 0);
                                                                })    
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }elseif ($request->posisi == "jahit") {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) {
                                                                    $d->where('soom', 1)
                                                                    ->where('jahit', 0)
                                                                    ->where('bawahan', 0);
                                                                })                                                                
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }else {
                $gdRequestOperator = GudangJahitRequestOperator::where('purchaseId', $request->purchaseId)
                                                                ->where('jenisBaju', $request->jenisBaju)
                                                                ->where('ukuranBaju', $request->ukuranBaju)
                                                                ->Where(function ($d) {
                                                                    $d->where('soom', 1)
                                                                    ->where('jahit', 1)
                                                                    ->where('bawahan', 0);
                                                                })                                                                
                                                                ->whereDate('created_at', date('Y-m-d'))->get();
            }

            // dd($gdRequestOperator);
            if (isset($request->operatorReqPurchaseId)) {
                for ($i=0; $i < count($request['operatorReqPurchaseId']); $i++) { 
                    if ($request['operatorReqPurchaseId'][$i] == null) {
                        continue;
                    }                    
                    
                    if ($index != 0) {
                        $getCheck = false;                        
                        for ($j=0; $j < count($checkId); $j++) { 
                            if($request['operatorReqPurchaseId'][$i] == $checkId[$j]['purchase']){
                                $getCheck = true;
                                $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                                for ($k=0; $k < count($operatorReqId); $k++) { 
                                    if (!in_array($operatorReqId[$k], $checkId[$j]['operatorReqId'])) {
                                        $checkId[$j]['operatorReqId'][] = $operatorReqId[$k];
                                        $checkId[$j]['jumlah'] += 1;
                                    }            
                                }
                            }
                        }
                        if ($getCheck == false) {
                            $checkId[$index]['purchase'] = $request['operatorReqPurchaseId'][$i];
                            $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                            for ($j=0; $j < count($operatorReqId); $j++) { 
                                $checkId[$index]['operatorReqId'][] = $operatorReqId[$j];
                            }
                            $checkId[$index]['jumlah'] = count($operatorReqId);
                            $index++;
                        }
                    }else {
                        $checkId[$index]['purchase'] = $request['operatorReqPurchaseId'][$i];
                        $operatorReqId = explode(",", $request['operatorReqId'][$i]);
                        for ($j=0; $j < count($operatorReqId); $j++) { 
                            $checkId[$index]['operatorReqId'][] = $operatorReqId[$j];
                        }
                        $checkId[$index]['jumlah'] = count($operatorReqId);
                        $index++;
                    }    
                }
            }
            // dd($checkId);
            if (!isset($request->jumlahBaju)) {
                $request->jumlahBaju = 12;
            } 
            $i = 0;
            foreach ($gdRequestOperator as $operator) {
                if (isset($request->operatorReqPurchaseId)) {
                    $cek = false;
                    for ($j=0; $j < count($checkId); $j++) { 
                        if ($operator->purchaseId == $checkId[$j]['purchase']) {
                            $cek = true;
                            if (!isset($request->jumlahBaju)) {
                                $request->jumlahBaju -= $checkId[$j]['jumlah'];
                            }
                            
                            if (!in_array($operator->id, $checkId[$j]['operatorReqId'])) {
                                if (!in_array($operator->id, $reqOperatorId)) {
                                    if ($i < $request->jumlahBaju) {
                                        $reqOperatorId[$i] = $operator->id;
                                        $i++;                                    
                                    }                                   
                                }
                            }
                        }                        
                    }
                    if ($cek == false) {
                        if (!in_array($operator->id, $reqOperatorId) && $i < $request->jumlahBaju) {
                            $reqOperatorId[$i] = $operator->id;
                            $i++;
                        }
                    }
                } else {
                    if (!in_array($operator->id, $reqOperatorId) && $i < $request->jumlahBaju) {
                        $reqOperatorId[$i] = $operator->id;
                        $i++;
                    }
                }
            }            
            
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
                    'jumlahBaju' => count($reqOperatorId)
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
        foreach ($gudangJahitDetail as $detail) {
            $detail->qty = $detail->qty/12; 
        }

        return view('gudangJahit.request.detail', ['gdJahit' => $gudangJahit, 'gdJahitDetail' => $gudangJahitDetail]);

    }

    //Operator Request From Gudang Baju Stok Opname
    public function gOperator()
    {
        $dataPemindahan = [];
        $i = 0;        
        $tempJenisBaju = '';        
        $tempUkuranBaju = '';        
        $gdRequestOperator = GudangJahitRequestOperator::select("*", DB::raw('count(*) as jumlah'))->groupBy('jenisBaju', 'ukuranBaju')->whereDate('created_at', date('Y-m-d'))->get();
        foreach ($gdRequestOperator as $reqOperator) {
            $reqOperator->totalDz =  (int)($reqOperator->jumlah/12);
            $sisa = ($reqOperator->jumlah%12);
            if ($sisa != 0) {
                $reqOperator->sisa = $sisa;
            }  
        }
        $gdJahitBasis = GudangJahitBasis::groupBy('posisi')->whereDate('created_at', date('Y-m-d'))->get();
        $gdJahitRekap = GudangJahitRekap::orderBy('tanggal', 'desc')->groupBy('posisi', 'tanggal')->get();
        // dd($gdJahitRekap);
        // $dataPemindahan = GudangJahitRequestOperator::select('*', DB::raw('count(*) as jumlah'))->groupBy('jenisBaju', 'ukuranBaju')->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->get();
        $pindahan = GudangJahitRequestOperator::where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->orderby('jenisBaju', 'asc')->get();

        foreach ($pindahan as $detail) {
            $checkBatilDetail = GudangBatilMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            if ($checkBatilDetail == null) {
                if ($i != 0 && $detail->jenisBaju == $tempJenisBaju && $detail->ukuranBaju == $tempUkuranBaju) {
                    $dataPemindahan[$i-1]['jumlahBaju'] += 1;
                } else {
                    $dataPemindahan[$i] = [
                        'tanggal' => date('d F Y', strtotime($detail->created_at)),
                        'jenisBaju' => $detail->jenisBaju,
                        'ukuranBaju' => $detail->ukuranBaju,
                        'jumlahBaju' => 1,
                    ];
                    $i++;
                }
                
                $tempJenisBaju = $detail->jenisBaju;        
                $tempUkuranBaju = $detail->ukuranBaju;                
            }
        }
        $gdBatilMasuk = GudangBatilMasuk::where('tanggal', date('Y-m-d'))->get();
        if (count($gdBatilMasuk) != 0) {
            $gdBatilMasukDetail = GudangBatilMasukDetail::select('*', DB::raw('count(*) as jumlah'))->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->whereDate('created_at', date('Y-m-d'))->get();
        }else {
            $gdBatilMasukDetail = [];
        }
        
        return view('gudangJahit.operator.index', ['operatorRequest' => $gdRequestOperator, 'jahitBasis' => $gdJahitBasis, 'jahitRekap' => $gdJahitRekap, 'dataPemindahan' => $dataPemindahan, 'gdBatilMasuk' => $gdBatilMasukDetail]);
    }

    public function gOperatorDetail($jenisBaju, $ukuranBaju)
    {
        $gdRequestOperator = GudangJahitRequestOperator::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->whereDate('created_at', date('Y-m-d'))->get();

        return view('gudangJahit.operator.detail', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorCreate()
    {
        $gdRequestOperator = GudangBajuStokOpname::select('jenisBaju')->groupBy('jenisBaju')->get();

        return view('gudangJahit.operator.create', ['operatorRequest' => $gdRequestOperator]);
    }

    public function gOperatorStore(Request $request)
    {
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $gdBajuStokOpnameId = [];
                $gdRequestOperator = GudangBajuStokOpname::where('purchaseId', $request['purchaseId'][$i])->where('jenisBaju', $request['jenisBaju'][$i])->where('ukuranBaju', $request['ukuranBaju'][$i])->where('soom', $request['soom'][$i])->where('jahit', $request['jahit'][$i])->where('bawahan', $request['bawahan'][$i])->get();
                foreach ($gdRequestOperator as $value) {
                    $gdBajuStokOpnameId[] = $value->id;
                }
                
                for ($j=0; $j < $request['jumlah'][$i]*12; $j++) { 
                    $createOperator = GudangJahitRequestOperator::OperatorBajuCreate($gdBajuStokOpnameId[$j], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i], $request['soom'][$i], $request['bawahan'][$i], $request['jahit'][$i], \Auth::user()->id);
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

    public function gOperatorDataMaterial(Request $request)
    {        
        $data = [];
        if ($request->ukuranBaju == null) {
            $gdRequestOperator = GudangBajuStokOpname::select('ukuranBaju')->where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->groupBy('ukuranBaju')->get();
            foreach ($gdRequestOperator as $operator) {
                if (!in_array($operator->ukuranBaju, $data)) {
                    $data[] = $operator->ukuranBaju;
                }
            }
        } else {
            $gdRequestOperator = GudangBajuStokOpname::where('purchaseId', $request->purchaseId)->where('jenisBaju', $request->jenisBaju)->where('ukuranBaju', $request->ukuranBaju)->where('soom', $request->soom)->where('jahit', $request->jahit)->where('bawahan', $request->bawahan)->get();
            $data = count($gdRequestOperator);
            // $cekOperator = GudangJahitRequestOperator::whereDate('created_at','!=', date('Y-m-d'))->delete();
            // dd($cekOperator);
            foreach ($gdRequestOperator as $value) {
                $cekOperator = GudangJahitRequestOperator::where('gdBajuStokOpnameId', $value->id)->where('soom', $request->soom)->where('jahit', $request->jahit)->where('bawahan', $request->bawahan)->whereDate('created_at', date('Y-m-d'))->first();
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
        if ($request->jumlah_data != 0) {
            for ($i=0; $i < $request->jumlah_data; $i++) { 
                $gdBajuStokOpnameId = [];
                $gdRequestOperator = GudangBajuStokOpname::where('purchaseId', $request['purchaseId'][$i])->where('jenisBaju', $request['jenisBaju'][$i])->where('ukuranBaju', $request['ukuranBaju'][$i])->where('soom', 0)->where('jahit', 0)->where('bawahan', 0)->get();
                foreach ($gdRequestOperator as $value) {
                   $gdBajuStokOpnameId[] = $value->id;
                }

                for ($j=0; $j < $request['jumlah'][$i]*12; $j++) { 
                    $createOperator = GudangJahitRequestOperator::OperatorBajuCreate($gdBajuStokOpnameId[$j], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i], $request['soom'][$i], $request['bawahan'][$i], $request['jahit'][$i], \Auth::user()->id);
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
        $totalJahitPegawai = [];
        $total = 0;
        $getPegawai = GudangJahitRekap::where('id', $id)->first();
        $getDetailPegawai = GudangJahitRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdJahitRekapId', $getPegawai->id)->groupBy('pegawaiId', 'tanggal', 'purchaseId', 'jenisBaju', 'ukuranBaju')->get();
        foreach ($getDetailPegawai as $detailPegawai) {
            $getCountPegawai = GudangJahitRekapDetail::select(DB::raw('count(*) as jumlah'))->where('pegawaiId', $detailPegawai->pegawaiId)->groupBy('pegawaiId', 'tanggal', 'purchaseId', 'jenisBaju', 'ukuranBaju')->where('tanggal', date('Y-m-d'))->get();
            if (!in_array($detailPegawai->pegawaiId, $totalJahitPegawai)) {
                $totalJahitPegawai[] = $detailPegawai->pegawaiId;
                foreach ($getCountPegawai as $countPegawai) {
                    $total += $countPegawai->jumlah;
                }
                $detailPegawai->jumlahTotal = $total/12;
                $detailPegawai->rowSpan = count($getCountPegawai);
                $total = 0;
            }
            $detailPegawai->posisi = $getPegawai->posisi;        
        }

        return view('gudangJahit.rekap.detail', ['pegawais' => $getDetailPegawai]);
    }

    public function gRekapUpdate($id) 
    {
        $getPegawai = GudangJahitRekap::where('id', $id)->whereDate('created_at', date('Y-m-d'))->first();
        $getDetailPegawai = GudangJahitRekapDetail::select('*', DB::raw('count(*) as jumlah'))->where('gdJahitRekapId', $getPegawai->id)->groupBy('pegawaiId', 'tanggal', 'jenisBaju', 'ukuranBaju')->get();
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
            return redirect('GJahit/rekap/update/' . $request->id . '');
        }
    }

    public function gRekapUpdateDelete($rekapId, $pegawaiId, $purchaseId, $jenisBaju, $ukuranBaju, $posisi)
    {
        $getPegawai = GudangJahitRekap::where('id', $rekapId)->first();
        $CheckPegawai = GudangJahitRekapDetail::where('gdJahitRekapId', $getPegawai->id)->get();
        $getDetailPegawai = GudangJahitRekapDetail::where('gdJahitRekapId', $getPegawai->id)
                                                    ->where('pegawaiId', $pegawaiId)
                                                    ->where('purchaseId', $purchaseId)
                                                    ->where('jenisBaju', $jenisBaju)
                                                    ->where('ukuranBaju', $ukuranBaju)
                                                    ->get();
        foreach ($getDetailPegawai as $detailPegawai) {
            $operatorReq = GudangJahitRequestOperator::where('gdBajuStokOpnameId', $detailPegawai->gdBajuStokOpnameId)->first();
            if ($operatorReq != null) {
                $operatorReqUpdate = GudangJahitRequestOperator::GudangOperatorBajuUpdateField($posisi, 0, $operatorReq->id);
                if ($operatorReqUpdate == 1) {
                    $bajuStokOpname = GudangBajuStokOpname::bajuUpdateField($posisi, 0, $operatorReq->gdBajuStokOpnameId);
                }
            }
        }

        if ($bajuStokOpname == 1) {
            $deleteDetailPegawai = GudangJahitRekapDetail::where('gdJahitRekapId', $getPegawai->id)
                                                            ->where('pegawaiId', $pegawaiId)
                                                            ->where('purchaseId', $purchaseId)
                                                            ->where('jenisBaju', $jenisBaju)
                                                            ->where('ukuranBaju', $ukuranBaju)
                                                            ->delete();

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


    public function gKeluarCreate($jenisBaju, $ukuranBaju, $date)
    {
        // $dataPemindahan = GudangJahitRequestOperator::select('*')->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', date('Y-m-d'))->get();
        $dataPemindahan = [];
        $pindahan = GudangJahitRequestOperator::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', $date)->get();
        $getBajuDetail = GudangJahitRequestOperator::select('*', DB::raw('count(*) as jumlah'))->where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', $date)->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')->first();
        foreach ($pindahan as $detail) {
            $checkBatilDetail = GudangBatilMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            if ($checkBatilDetail != null) {
                $getBajuDetail->jumlah -= 1;
                $getBajuDetail->ambilPcs = $getBajuDetail->jumlah;
            }else {
                $dataPemindahan[] = $detail->gdBajuStokOpnameId;
                $getBajuDetail->ambilPcs = $getBajuDetail->jumlah/12;
            }            
        }
        
        $getBajuDetail->dataPemindahan = preg_replace("/[^0-9]/", ",", json_encode($dataPemindahan));

        return view('gudangJahit.keluar.create', ['pemindahan' => $getBajuDetail]);
    }

    public function gKeluarStore(Request $request)
    {
        // dd($request);
        if (count($request->gdBajuStokOpnameId) != 0 && count($request->totalDz) != 0) {
            $batilMasuk = GudangBatilMasuk::createBatilMasuk();           

            for ($i=0; $i < count($request->gdBajuStokOpnameId); $i++) { 
                $gdBajuStokOpnameId = explode(",", $request['gdBajuStokOpnameId'][$i]);
                for ($j=1; $j < count($gdBajuStokOpnameId)-1; $j++) { 
                    if ($j <= ($request['totalDz'][$i]*12)) {
                        $batilMasukDetail = GudangBatilMasukDetail::createGudangBatilMasukDetail($batilMasuk, $gdBajuStokOpnameId[$j], $request['purchaseId'][$i], $request['jenisBaju'][$i], $request['ukuranBaju'][$i], 0);
                    }
                }
                
            }

            if ($batilMasukDetail == 1) {
                return redirect('GJahit/operator');
            }
        } else {
            return redirect('GJahit/operator');
        }
        
    }

    public function gKeluarDetail($jenisBaju, $ukuranBaju, $date)
    {
        $dataPemindahan = [];
        $pindahan = GudangJahitRequestOperator::where('jenisBaju', $jenisBaju)->where('ukuranBaju', $ukuranBaju)->where('soom', 1)->where('jahit', 1)->where('bawahan', 1)->whereDate('created_at', $date)->get();
        foreach ($pindahan as $detail) {
            $checkBatilDetail = GudangBatilMasukDetail::where('gdBajuStokOpnameId', $detail->gdBajuStokOpnameId)->first();
            if ($checkBatilDetail == null) {
                if (!in_array($detail->gdBajuStokOpnameId, $dataPemindahan)) { 
                    $dataPemindahan[] = [
                        'tanggal' => date('d F Y', strtotime($detail->created_at)),
                        'gdBajuStokOpnameId' => $detail->gdBajuStokOpnameId,
                        'purchase' => $detail->purchase->kode,
                        'jenisBaju' => $detail->jenisBaju,
                        'ukuranBaju' => $detail->ukuranBaju,
                        'soom' => $detail->soom,
                        'jahit' => $detail->jahit,
                        'bawahan' => $detail->bawahan,
                    ];
                }
            }
        }
        
        for ($i=0; $i < count($dataPemindahan); $i++) { 
            $gdRekapDetail = GudangJahitRekapDetail::where('gdBajuStokOpnameId', $dataPemindahan[$i]['gdBajuStokOpnameId'])->get();
            foreach ($gdRekapDetail as $detail) {
                if ($detail->rekap->posisi == "Soom") {
                   $dataPemindahan[$i]['soomName'] = $detail->pegawai->nama;
                }
                if ($detail->rekap->posisi == "Jahit") {
                    $dataPemindahan[$i]['jahitName'] = $detail->pegawai->nama;

                }
                if ($detail->rekap->posisi == "Bawahan") {
                    $dataPemindahan[$i]['bawahanName'] = $detail->pegawai->nama;

                }
            }
        }

        return view('gudangJahit.keluar.detail', ['operatorRequest' => $dataPemindahan]);
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

        $gudangPotongTerima = GudangJahitReject::updateStatusDiterima('statusProses', $statusDiterima, $id);

        if ($gudangPotongTerima == 1) {
            return redirect('GJahit/reject');
        }
    }

    public function gRejectKembali($id)
    {
        $id = $id;  
        $statusDiterima = 2;  

        $gudangPotongTerima = GudangJahitReject::updateStatusDiterima('statusProses', $statusDiterima, $id);

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
