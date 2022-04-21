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
}
