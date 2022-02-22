<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudangInspeksiStokOpnameDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksi_stokopname_detail';

    public static function createInspeksiProsesDetail($gudangInspeksi, $roll, $berat, $panjang, $lubang, $plex, $belang, $tanah, $sambung, $jarum, $ket)
    {
        $AddDetailInspeksiProses = new gudangInspeksiStokOpnameDetail;
        $AddDetailInspeksiProses->gdInspeksiStokId = $gudangInspeksi;
        $AddDetailInspeksiProses->roll = $roll;
        $AddDetailInspeksiProses->berat = $berat;
        $AddDetailInspeksiProses->yard = $panjang;
        $AddDetailInspeksiProses->lubang = $lubang;
        $AddDetailInspeksiProses->plek = $plex;
        $AddDetailInspeksiProses->belang = $belang;
        $AddDetailInspeksiProses->tanah = $tanah;
        $AddDetailInspeksiProses->bs = $sambung;
        $AddDetailInspeksiProses->jarum = $jarum;
        $AddDetailInspeksiProses->keterangan = $ket;
        
        $AddDetailInspeksiProses->created_at = date('Y-m-d H:i:s');

        if ($AddDetailInspeksiProses->save()) {
            return 1;
        } else {
            return 0;
        }
    }

}
