<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GudangRajutKeluarDetail;
use App\Models\GudangRajutMasukDetail;
use App\Models\GudangCuciKeluarDetail;
use App\Models\GudangCompactKeluarDetail;
use App\Models\GudangCompactMasukDetail;
use App\Models\GudangInspeksiKeluarDetail;
use App\Models\gudangInspeksiStokOpname;
use App\Models\gudangInspeksiStokOpnameDetail;

use App\Models\GudangPotongKeluarDetail;
use App\Models\GudangPotongProses;
use App\Models\GudangPotongProsesDetail;
use App\Models\GudangJahitRequestOperator;
use App\Models\GudangBatilStokOpname;
use App\Models\GudangControlStokOpname;
use App\Models\GudangSetrikaStokOpname;

use DB;

class DashboardTVController extends Controller
{
    public function bahanBaku()
    {
        return view('dashboardTV.bahanBaku');
    }

    
    public function getBahanBaku()
    {
        $data = [];
        $rajutKeluar = GudangRajutKeluarDetail::select("*", DB::raw('sum(qty) as jumlah'))->first();
        $rajutKeluar->jumlah = round($rajutKeluar->jumlah/181.44,2);
        $rajutKeluar->kategori = "Proses Rajut";
        $rajutKeluar->satuan = "BAL";
        
        $cuciKeluar = GudangCuciKeluarDetail::select("*", DB::raw('count(*) as jumlah'))->first();
        $cuciKeluar->kategori = "Proses Cuci";
        $cuciKeluar->satuan = "ROLL";
        
        $compactKeluar = GudangCompactKeluarDetail::select("*", DB::raw('count(*) as jumlah'))->first();
        $compactKeluar->kategori = "Proses Compact";
        $compactKeluar->satuan = "ROLL";
        
        $inspeksiKeluar = GudangInspeksiKeluarDetail::select("*", DB::raw('count(*) as jumlah'))->first();
        $inspeksiKeluar->kategori = "Proses Inspeksi";
        $inspeksiKeluar->satuan = "ROLL";

        $rajutMasuk = GudangRajutMasukDetail::all();
        foreach ($rajutMasuk as $rajut) {
            $rajut->purchaseId = $rajut->purchase->kode;
            $rajut->materialId = $rajut->material->nama;
        }

        $cuciMasuk = GudangCompactKeluarDetail::all();
        foreach ($cuciMasuk as $cuci) {
            $cuci->purchaseId = $cuci->purchase->kode;
            $cuci->materialId = $cuci->material->nama;
        }

        $compactMasuk = GudangCompactMasukDetail::all();
        foreach ($compactMasuk as $compact) {
            $compact->purchaseId = $compact->purchase->kode;
            $compact->materialId = $compact->material->nama;
        }
        
        $inspeksiMasuk = gudangInspeksiStokOpname::all();
        foreach ($inspeksiMasuk as $inspeksi) {
            $inspeksiDetail = gudangInspeksiStokOpnameDetail::select("*", DB::raw('sum(lubang) as lubang'), 
                                                                          DB::raw('sum(plek) as plek'), 
                                                                          DB::raw('sum(belang) as belang'), 
                                                                          DB::raw('sum(tanah) as tanah'), 
                                                                          DB::raw('sum(bs) as bs'), 
                                                                          DB::raw('sum(jarum) as jarum'))
                                                                          ->where('gdInspeksiStokId', $inspeksi->id)
                                                                          ->get();
            if (count($inspeksiDetail) != 0) {
                foreach ($inspeksiDetail as $detail) {
                    $inspeksi->lubang = $detail->lubang;
                    $inspeksi->plek = $detail->plek;
                    $inspeksi->belang = $detail->belang;
                    $inspeksi->tanah = $detail->tanah;
                    $inspeksi->bs = $detail->bs;
                    $inspeksi->jarum = $detail->jarum;
                    $inspeksi->berat = $detail->berat;
                }
                $inspeksi->detail = $inspeksiDetail;
            }
            $inspeksi->purchaseId = $inspeksi->purchase->kode;
            $inspeksi->materialId = $inspeksi->material->nama;
        }
        
        $data['header'][] = $rajutKeluar;
        $data['header'][] = $cuciKeluar;
        $data['header'][] = $compactKeluar;
        $data['header'][] = $inspeksiKeluar;

        $data['table'][] = $rajutMasuk;
        $data['table'][] = $cuciMasuk;
        $data['table'][] = $compactMasuk;
        $data['table'][] = $inspeksiMasuk;
        
        return json_encode($data);
    }

    public function produksi()
    {
        return view('dashboardTV.produksi');
    }

    public function getProduksi()
    {
        $data = [];
        $potongKeluar = GudangPotongKeluarDetail::select("*", DB::raw('count(*) as jumlah'))->first();
        $potongKeluar->kategori = "Proses Potong";
        $potongKeluar->satuan = "Roll";
        
        $JahitStok = GudangJahitRequestOperator::select("*", DB::raw('count(*) as jumlah'))->first();
        $JahitStok->jumlah = $JahitStok->jumlah/12;
        $JahitStok->kategori = "Proses Jahit";
        $JahitStok->satuan = "Dz";
        
        $batilStok = GudangBatilStokOpname::select("*", DB::raw('count(*) as jumlah'))->first();
        $batilStok->jumlah = $batilStok->jumlah/12;
        $batilStok->kategori = "Proses Batil";
        $batilStok->satuan = "Dz";
        
        $controlStok = GudangControlStokOpname::select("*", DB::raw('count(*) as jumlah'))->first();
        $controlStok->jumlah = $controlStok->jumlah/12;
        $controlStok->kategori = "Proses Control";
        $controlStok->satuan = "Dz";

        $setrikaStok = GudangSetrikaStokOpname::select("*", DB::raw('count(*) as jumlah'))->where('statusPacking', null)->where('statusReject', 0)->first();
        $setrikaStok->jumlah = $setrikaStok->jumlah/12;
        $setrikaStok->kategori = "Proses Setrika";
        $setrikaStok->satuan = "Dz";

        $packingStok = GudangSetrikaStokOpname::select("*", DB::raw('count(*) as jumlah'))->where('statusPacking', 0)->where('statusReject', 0)->first();
        $packingStok->jumlah = $packingStok->jumlah/12;
        $packingStok->kategori = "Proses Packing";
        $packingStok->satuan = "Dz";

        $potongProses = GudangPotongProsesDetail::select("*", DB::raw('sum(hasilDz) as jumlah'))->groupBy('jenisBaju', 'ukuranBaju')->get();       

        $batilProses = GudangBatilStokOpname::select("*", DB::raw('count(*) as jumlah'))->groupBy('purchaseId','jenisBaju', 'ukuranBaju')->get();
        foreach ($batilProses as $batil) {
            $batil->purchaseId = $batil->purchase->kode;            
            $batil->jumlah = $batil->jumlah/12;
        }

        $controlProses = GudangControlStokOpname::select("*", DB::raw('count(*) as jumlah'))->groupBy('purchaseId','jenisBaju', 'ukuranBaju')->get();
        foreach ($controlProses as $control) {
            $control->purchaseId = $control->purchase->kode;            
            $control->jumlah = $control->jumlah/12;
        }

        $setrikaProses = GudangSetrikaStokOpname::select("*", DB::raw('count(*) as jumlah'))->where('statusPacking', null)->where('statusReject', 0)->groupBy('purchaseId','jenisBaju', 'ukuranBaju')->get();
        foreach ($setrikaProses as $setrika) {
            $setrika->purchaseId = $setrika->purchase->kode;            
            $setrika->jumlah = $setrika->jumlah/12;
        }

        $packingProses = GudangSetrikaStokOpname::select("*", DB::raw('count(*) as jumlah'))->where('statusPacking', 0)->where('statusReject', 0)->groupBy('purchaseId','jenisBaju', 'ukuranBaju')->get();
        foreach ($packingProses as $packing) {
            $packing->purchaseId = $packing->purchase->kode;            
            $packing->jumlah = $packing->jumlah/12;
        }       
        
        
        $data['header'][] = $potongKeluar;
        $data['header'][] = $JahitStok;
        $data['header'][] = $batilStok;
        $data['header'][] = $controlStok;
        $data['header'][] = $setrikaStok;
        $data['header'][] = $packingStok;

        $data['table'][] = $potongProses;
        $data['table'][] = $batilProses;
        $data['table'][] = $controlProses;
        $data['table'][] = $setrikaProses;
        $data['table'][] = $packingProses;
        
        return json_encode($data);
    }
}
