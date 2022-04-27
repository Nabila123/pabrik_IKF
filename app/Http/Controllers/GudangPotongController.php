<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\GudangPotongKeluar;
use App\Models\GudangPotongKeluarDetail;
use App\Models\GudangPotongProses;
use App\Models\GudangPotongProsesDetail;
use App\Models\GudangPotongRequest;
use App\Models\GudangPotongRequestDetail;
use App\Models\GudangJahitMasuk;
use App\Models\GudangJahitMasukDetail;
use App\Models\GudangJahitReject;
use App\Models\GudangJahitRejectDetail;
use App\Models\Pegawai;
use App\Models\JenisBaju;
use DB;

class GudangPotongController extends Controller
{
    public function index()
    {
        $kains = GudangPotongKeluarDetail::all();

        $bajus = GudangPotongProsesDetail::select('jenisBaju', 'ukuranBaju')->groupBy('jenisBaju', 'ukuranBaju')->get();
        $data = GudangPotongProsesDetail::all();
        $dataJahit = GudangJahitMasukDetail::all();
        $dataKain=[];
        $kainPutih = 0;
        $jumlahPutih = 0;
        $kainInspeksi = 0;
        $jumlahInspeksi = 0;
        $dataStok=[];

        // dd($bajus, $data);
        foreach ($kains as $kain) {
            if ($kain->gdDetailMaterialId != null) {
                $dataKain["putih"]['nama'] = "Kain Putih";
                $kainPutih += $kain->berat;
                $dataKain["putih"]['berat'] = $kainPutih;
                $jumlahPutih += $kain->qty;
                $dataKain["putih"]['qty'] = $jumlahPutih;
            } else {
                $dataKain["inspeksi"]['nama'] = "Kain Putih Inspeksi";
                $kainInspeksi += $kain->berat;
                $dataKain["inspeksi"]['berat'] = $kainInspeksi;
                $jumlahInspeksi += $kain->qty;
                $dataKain["inspeksi"]['qty'] = $jumlahInspeksi;
            }
            
        }
        foreach ($bajus as $baju) {
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['nama'] = $baju->jenisBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['ukuran'] = $baju->ukuranBaju;
            $dataStok[$baju->jenisBaju."_".$baju->ukuranBaju]['qty'] = 0;
        }

        foreach ($data as $value) {
            $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] = $dataStok[$value->jenisBaju."_".$value->ukuranBaju]['qty'] + $value->hasilDz;
        }

        foreach ($dataJahit as $jahit) {
            $dataStok[$jahit->jenisBaju."_".$jahit->ukuranBaju]['qty'] = $dataStok[$jahit->jenisBaju."_".$jahit->ukuranBaju]['qty'] - ($jahit->qty/12);

        }

        return view('gudangPotong.index', ['dataKain' => $dataKain, 'dataStok' => $dataStok]);
    }

    public function getData(Request $request)
    {
        $gdPotong = GudangPotongKeluar::getPurchaseWithMaterial($request);

        return json_encode($gdPotong);
    }

    //Gudang Potong Request Bahan Jadi
    public function gRequest()
    {
        $gdPotongReq = GudangPotongRequest::all();
        return view('gudangPotong.request.index', ['gdPotongReq' => $gdPotongReq]);
    }

    public function gReqDetail($id)
    {
        $gdPotongRequest = GudangPotongRequest::where('id', $id)->first();
        $gdPotongRequestDetail = GudangPotongRequestDetail::where('gdPotongReqId', $gdPotongRequest->id)->get();
        
        if ($gdPotongRequest->statusDiterima == 0) {
            $gdPotongRequest->status = "Barang Belum Diproses";
            $gdPotongRequest->color = "#dc3545";
        }else{
            $gdPotongRequest->status = "Barang Sedang Diproses";
            $gdPotongRequest->color = "#28a745";
        }

        foreach ($gdPotongRequestDetail as $detail) {
            $dz = $detail->qty/12;
            $sisa = $detail->qty % 12;

            $detail->dz = (int)$dz." / ". $sisa;
        }

        return view('gudangPotong.request.detail', ['gdPotongRequest' => $gdPotongRequest, 'gdPotongRequestDetail' => $gdPotongRequestDetail]);
    }

    public function gReqTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangRajutTerima = GudangPotongRequest::updateStatusDiterima($id, $statusDiterima);

        if ($gudangRajutTerima == 1) {
            return redirect('GPotong/request');
        }
    }

    //Gudang Potong Keluar Bahan Baku
    public function gKeluar()
    {
        $gudangKeluar = GudangPotongKeluar::all();
        foreach ($gudangKeluar as $keluar) {
            $gudangKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId', $keluar->id)->get();
            $jahit = [];
            foreach ($gudangKeluarDetail as $keluarDetail) {
                $potongProses = GudangPotongProses::where('gPotongKId', $keluar->id)->where('purchaseId', $keluarDetail->purchaseId)->get();
                if (count($potongProses) != 0) {
                    foreach ($potongProses as $proses) {
                        $potongProsesDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $proses->id)->where('diameter', $keluarDetail->diameter)->where('gramasi', $keluarDetail->gramasi)->first();
                        if ($potongProsesDetail != null) {
                            $cekJahit = GudangJahitMasukDetail::select('*', DB::raw('sum(qty) as qty'))->where('gdPotongProsesId', $potongProsesDetail->id)->first();
                            if ($cekJahit != null && ($cekJahit->qty/12) == $potongProsesDetail->hasilDz) {
                                $jahit[] = 1;  
                            }else{
                                $jahit[] = 0;
                            }
                            
                            $keluar->cekPotong = 1;
                        }
                    }
                }else {
                    $keluar->cekPotong = 0;
                }                
            }

            if (!in_array(0, $jahit)) {
                $keluar->cekJahit = 1;
            }
        }

        // dd($gudangKeluar);
        return view('gudangPotong.keluar.index', ['gudangPotong' => $gudangKeluar]);
    }

    public function gKeluarDetail($id)
    {
        $gudangPotong = GudangPotongKeluar::where('id', $id)->first();
        $gudangPotongDetail = GudangPotongKeluarDetail::where('gdPotongKId', $gudangPotong->id)->get();
        $cekBahanPembantu = GudangPotongKeluarDetail::where('gdPotongKId', $id)->whereHas('material', function (Builder $query) {
                     $query->where('keterangan','LIKE', '%Bahan Bantu / Merchandise%');
                    })->get();

        return view('gudangPotong.keluar.detail', ['gudangPotong' => $gudangPotong, 'gudangPotongDetail' => $gudangPotongDetail,'cekBahanPembantu'=>$cekBahanPembantu]);
    }

    public function gKeluarTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangPotongTerima = GudangPotongKeluar::updateStatusDiterima($id, $statusDiterima);

        if ($gudangPotongTerima == 1) {
            return redirect('GPotong/keluar');
        }
    }

    public function gKeluarKembali($id)
    {
        $gPotongKeluar = GudangPotongKeluar::where('id', $id)->first();
        $gPotongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId', $id)->get();

        $gPotongProses = GudangPotongProses::where('gPotongKId', $gPotongKeluar->id)->get();
        foreach ($gPotongProses as $proses) {
            $gPotongProsesDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $proses->id)->get();
            $proses->prosesDetail = $gPotongProsesDetail;

            foreach ($gPotongProsesDetail as $prosesDetail) {
                $gdJahitMasuk = GudangJahitMasukDetail::where('gdPotongProsesId', $prosesDetail->id)->where('purchaseId', $proses->purchaseId)->where('jenisBaju', $prosesDetail->jenisBaju)->where('ukuranBaju', $prosesDetail->ukuranBaju)->get();
                if ($gdJahitMasuk != null) {
                    $prosesDetail->ambilDz = $prosesDetail->hasilDz;
                    foreach ($gdJahitMasuk as $jahitMasuk) {
                        $prosesDetail->ambilDz -= ($jahitMasuk->qty / 12);
                        $prosesDetail->ambilPcs = $prosesDetail->ambilDz*12;
                    }
                }else {
                    $prosesDetail->ambilDz = $prosesDetail->hasilDz;
                    $prosesDetail->ambilPcs = $prosesDetail->hasilDz*12;
                }
            }
        }

        // dd($gPotongProses);
        
        foreach ($gPotongKeluarDetail as $detail) {
            $purchaseId[] = $detail->purchaseId;
        }
        
        return view('gudangPotong.keluar.create', ['purchaseId' => $purchaseId, 'gPotong' => $gPotongKeluar, 'gPotongProses' => $gPotongProses]);
    }

    public function gKeluarKembaliStore(Request $request)
    {
        // dd($request);
        $jahitMasuk = new GudangJahitMasuk;
        $jahitMasuk->tanggal = date('Y-m-d');
        $jahitMasuk->userId = \Auth::user()->id;
        $jahitMasuk->created_at = date('Y-m-d H:i:s');
        if ($jahitMasuk->save()) {
            for ($j=0; $j < count($request->potongProses); $j++) { 
                if ($request['totalDz'][$j] != 0) {                        
                    $gdPotongProsesDetail = GudangPotongProsesDetail::where('id', $request['potongProses'][$j])->first();
                    $gdJahitDetail = GudangJahitMasukDetail::createGudangJahitMasukDetail($jahitMasuk->id, $gdPotongProsesDetail->id, $gdPotongProsesDetail->prosesPotong->purchaseId, $gdPotongProsesDetail->jenisBaju, $gdPotongProsesDetail->ukuranBaju, ($request['totalDz'][$j]*12));
                }
            }
        }

            if ($gdJahitDetail == 1) {
                return redirect('GPotong/keluar');
            }
        
    }

    //Gudang Potong Proses
    public function gProses()
    {
        $gudangPotongProses = GudangPotongProses::all();
        return view('gudangPotong.proses.index', ['gdPotongProses' => $gudangPotongProses]);
    }

    public function gProsesCreate()
    {
        $purchasePotong = [];
        $material = [];
        $jenisBaju = [];
        $index = 0;
        $pegawai = Pegawai::where('kodeBagian', 'potong')->get();
        $Baju = JenisBaju::orderBy('type', 'asc')->get();
        foreach ($Baju as $key) {
            if (!in_array($key->type, $jenisBaju)) {
                $jenisBaju[$key->type] = [];
            }
        }

        foreach ($Baju as $val) {
            $dataBaju = explode(" ", $val->jenis);
            for ($i=0; $i < count($dataBaju); $i++) { 
                $dataBaju[$i] = $dataBaju[$i];
            }

            $jenisBaju[$val->type][] = implode("-", $dataBaju)."-".$val->type; 
        }

        $gudangKeluar = GudangPotongKeluar::all();
        foreach ($gudangKeluar as $keluar) {
            $gudangKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId', $keluar->id)->get();
            foreach ($gudangKeluarDetail as $detail) {
                $prosesPotong = GudangPotongProses::join('gd_potongproses_detail','gd_potongproses_detail.gdPotongProsesId', 'gd_potongproses.id')
                                                    ->where('gd_potongproses.gPotongKId', $keluar->id)
                                                    ->where('gd_potongproses.purchaseId', $detail->purchaseId)
                                                    ->where('gd_potongproses_detail.diameter', $detail->diameter)
                                                    ->where('gd_potongproses_detail.gramasi', $detail->gramasi)
                                                    ->get(); 
                if (count($prosesPotong) == 0) {
                    $data = [
                        "id" => $detail->purchaseId,
                        "kode" => $detail->purchase->kode,              
                        "terima" => $keluar->statusDiterima,
                        "tanggal" => $keluar->tanggal,
                        "gdKeluarId" => $keluar->id,
                    ];
                    if (!in_array($data, $purchasePotong)) {
                        $purchasePotong[$index] = [
                            "id" => $detail->purchaseId,
                            "kode" => $detail->purchase->kode,              
                            "terima" => $keluar->statusDiterima,                         
                            "tanggal" => $keluar->tanggal,
                            "gdKeluarId" => $keluar->id,
                        ];
                        $index++;
                    }
                }

                $material = [
                    "materialId" => $detail->materialId,
                    "jenisId" => $detail->jenisId
                ];
            }
        }

        return view('gudangPotong.proses.create', ['pegawai' => $pegawai, 'jenisBaju' => $jenisBaju, 'purchaseId' => $purchasePotong, 'material' => $material]);
    }

    public function gProsesStore(Request $request)
    {
        $dataId = explode("-",$request->purchaseId);
        $gudangPotong = GudangPotongProses::createPotongProses($request->gdPotongKId, $request->pegawaiId, $dataId[0], $request->materialId, $request->jenisId, $request->jumlah, date('Y-m-d H:i:s'), \Auth::user()->id);
        if ($gudangPotong) {
            $potongProsesId = $gudangPotong;

            for ($i = 0; $i < $request->jumlah_data; $i++) {
                GudangPotongProsesDetail::createPotongProsesDetail($potongProsesId, $request->jmlPotong[$i], $request->beratPotong[$i],  $request->diameter, $request->gramasi, $request->beratRoll[$i], $request->jnsBaju[$i], $request->size[$i], $request->totalDZ[$i], $request->totalPcs[$i], $request->totalKG[$i], $request->skb[$i], $request->bs[$i], $request->kecil[$i], $request->ketek[$i], $request->ketekPot[$i], $request->sumbu[$i], $request->bunder[$i], $request->tKecil[$i], $request->tBesar[$i], $request->tangan[$i], $request->kPutih[$i], $request->kBelang[$i], \Auth::user()->id);                
            }
            return redirect('GPotong/proses');
        }
    }

    public function getDataDetailMaterial($purchaseId, $materialId, $diameter, $gramasi = "")
    {
        $dataId = explode("-",$purchaseId);
        $datas = [];
        if ($gramasi == "null") {        
            $potongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId',$dataId[1])->where('purchaseId',$dataId[0])->where('materialId',$materialId)->where('diameter',$diameter)->get();
            foreach ($potongKeluarDetail as $detail) {
                if (!in_array($detail->gramasi, $datas)) {
                    $datas[] = $detail->gramasi;
                }
            }
        }else{
            $potongKeluarDetail = GudangPotongKeluarDetail::where('gdPotongKId',$dataId[1])->where('purchaseId',$dataId[0])->where('materialId',$materialId)->where('diameter',$diameter)->where('gramasi',$gramasi)->get();
            foreach ($potongKeluarDetail as $detail) {
                $datas['gdPotongKId'] = $detail->gdPotongKeluar->id;
                $datas['jumlah'] = $detail->qty;
            }
        }

        return json_encode($datas);
    }

    public function gProsesDetail($id)
    {
        $gdPotong = GudangPotongProses::find($id);
        $gdPotongDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $gdPotong->id)->get();

        return view('gudangPotong.proses.detail', ['gdPotong' => $gdPotong, 'gdPotongDetail' => $gdPotongDetail]);
    }

    public function gProsesUpdate($id)
    {
        $jenisBaju = [];
        $gdPotongProses = GudangPotongProses::where('id', $id)->first();
        $Baju = JenisBaju::orderBy('type', 'asc')->get();
        foreach ($Baju as $key) {
            if (!in_array($key->type, $jenisBaju)) {
                $jenisBaju[$key->type] = [];
            }
        }

        foreach ($Baju as $val) {
            $dataBaju = explode(" ", $val->jenis);
            for ($i=0; $i < count($dataBaju); $i++) { 
                $dataBaju[$i] = $dataBaju[$i];
            }

            $jenisBaju[$val->type][] = implode("-", $dataBaju)."-".$val->type; 
        }
        
        $gdPotongProsesDetail = GudangPotongProsesDetail::where('gdPotongProsesId', $gdPotongProses->id)->get();

        return view('gudangPotong.proses.update', ['gdPotongProses' => $gdPotongProses, 'jenisBaju' => $jenisBaju, 'gdPotongProsesDetail' => $gdPotongProsesDetail]);
    }

    public function gProsesUpdatePotong(Request $request)
    {
       if ($request->jumlah_data > 0) {
            for ($i = 0; $i < $request->jumlah_data; $i++) {
                GudangPotongProsesDetail::createPotongProsesDetail($request->id, $request->jmlPotong[$i], $request->beratPotong[$i],  $request->diameter, $request->gramasi, $request->beratRoll[$i], $request->jnsBaju[$i], $request->size[$i], $request->totalDZ[$i], $request->totalPcs[$i], $request->totalKG[$i], $request->skb[$i], $request->bs[$i], $request->kecil[$i], $request->ketek[$i], $request->ketekPot[$i], $request->sumbu[$i], $request->bunder[$i], $request->tKecil[$i], $request->tBesar[$i], $request->tangan[$i], $request->kPutih[$i], $request->kBelang[$i], \Auth::user()->id);                
            }
            return redirect('GPotong/proses');
        } else {
            return redirect('GPotong/proses');
        }
    }

    public function gProsesUpdateDelete($detailId, $potongProsesId)
    {
        $inspeksiDetail = GudangPotongProsesDetail::where('id', $detailId)->delete();
        if ($inspeksiDetail) {
            return redirect('GPotong/proses/update/' . $potongProsesId . '');
        }
    }

    public function gProsesDelete(Request $request)
    {
        GudangPotongProsesDetail::where('gdPotongProsesId', $request['gdPotongProsesId'])->delete();        
        GudangPotongProses::where('id', $request['gdPotongProsesId'])->delete();   
                
        return redirect('GPotong/proses');
    }


    public function gReject()
    {
        $gdPotongReject = GudangJahitReject::where('rejectTo', 'Gudang Potong')->get();

        return view('gudangPotong.reject.index', ['jahitReject' => $gdPotongReject]);
    }

    public function gRejectTerima($id)
    {
        $id = $id;  
        $statusDiterima = 1;  

        $gudangPotongTerima = GudangJahitReject::updateStatusDiterima('statusProses', $statusDiterima, $id);

        if ($gudangPotongTerima == 1) {
            return redirect('GPotong/reject');
        }
    }

    public function gRejectKembali($id)
    {
        $id = $id;  
        $statusDiterima = 2;  

        $gudangPotongTerima = GudangJahitReject::updateStatusDiterima('statusProses', $statusDiterima, $id);

        if ($gudangPotongTerima == 1) {
            return redirect('GPotong/reject');
        }
    }

    public function gRejectDetail($id)
    {
        $gdJahitReject = GudangJahitReject::where('id', $id)->first();
        $gdJahitRejectDetail = GudangJahitRejectDetail::where('gdJahitRejectId', $gdJahitReject->id)->get();

        return view('gudangPotong.reject.detail', ['jahitRejectDetail' => $gdJahitRejectDetail]);
    }
}
