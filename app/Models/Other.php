<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Other extends Model
{
    use HasFactory;

    public static function getSearch($model, $purchaseId = '', $tglMulai = '', $tglSelesai = '')
    {
       if ($model == "jahit") {
            $getData = GudangBajuStokOpname::select('*', DB::raw('count(*) as jumlah'))
                            ->where('purchaseId', $purchaseId)
                            ->orWhere(function ($b) use ($tglMulai,$tglSelesai) {
                                $b->whereBetween('updated_at', [$tglMulai, $tglSelesai]);
                            })
                            ->where('soom', 1)
                            ->where('jahit', 1)
                            ->where('bawahan', 1)
                            ->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')
                            ->orderBy('jenisBaju')
                            ->get();
       }

       if ($model == "batil") {
            $getData = GudangBatilStokOpname::select('*', DB::raw('count(*) as jumlah'))
                            ->where('purchaseId', $purchaseId)
                            ->orWhere(function ($b) use ($tglMulai,$tglSelesai) {
                                $b->whereBetween('updated_at', [$tglMulai, $tglSelesai]);
                            })
                            ->where('statusBatil', 1)
                            ->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')
                            ->orderBy('jenisBaju')
                            ->get();
        }
       
        if ($model == "control") {
            $getData = GudangControlStokOpname::select('*', DB::raw('count(*) as jumlah'))
                            ->where('purchaseId', $purchaseId)
                            ->orWhere(function ($b) use ($tglMulai,$tglSelesai) {
                                $b->whereBetween('updated_at', [$tglMulai, $tglSelesai]);
                            })
                            ->where('statusControl', 1)
                            ->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')
                            ->orderBy('jenisBaju')
                            ->get();
        }

        if ($model == "setrika") {
            $getData = GudangSetrikaStokOpname::select('*', DB::raw('count(*) as jumlah'))
                            ->where('purchaseId', $purchaseId)
                            ->orWhere(function ($b) use ($tglMulai,$tglSelesai) {
                                $b->whereBetween('updated_at', [$tglMulai, $tglSelesai]);
                            })
                            ->where('statusSetrika', 1)
                            ->groupBy('purchaseId', 'jenisBaju', 'ukuranBaju')
                            ->orderBy('jenisBaju')
                            ->get();
        }
       $html = "";
       $no = 1;
        foreach ($getData as $data) {
            $kode = $data->purchase->kode;

            $html .= '<tr>';
                $html .= '<td>'. $no .'</td>';
                $html .= '<td>'. $kode .'</td>';
                $html .= '<td>'. $data->jenisBaju .'</td>';
                $html .= '<td>'. $data->ukuranBaju .'</td>';
                $html .= '<td>'. $data->jumlah/12 .'</td>';
            $html .= '</tr>';
        }


       return $html;
    }
}
