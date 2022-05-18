<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongProsesDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_potongproses_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function prosesPotong()
    {
        return $this->hasOne('App\Models\GudangPotongProses','id','gdPotongProsesId');
    }

    public static function createPotongProsesDetail($gdPotongProsesId, $jumlahRoll, $beratPotong,  $diameter, $gramasi, $beratRoll, $jenisBaju, $ukuranBaju, $hasilDz, $sisaPcs, $hasilKg, $skb, $bs, $aKecil, $aKetek, $aKetekPotong, $aSumbu, $aBunder, $aTanggungKecil, $aTanggungBesar, $aTangan, $aKepalaPutih, $aKepalaBelang, $userId)
    {
        $AddDetailPotongProses = new GudangPotongProsesDetail;
        $AddDetailPotongProses->gdPotongProsesId = $gdPotongProsesId;
        $AddDetailPotongProses->jumlahRoll = $jumlahRoll;
        $AddDetailPotongProses->beratPotong = $beratPotong;
        $AddDetailPotongProses->diameter = $diameter;
        $AddDetailPotongProses->gramasi = $gramasi;
        $AddDetailPotongProses->beratRoll = $beratRoll;
        $AddDetailPotongProses->jenisBaju = $jenisBaju;
        $AddDetailPotongProses->ukuranBaju = $ukuranBaju;
        $AddDetailPotongProses->hasilDz = $hasilDz;
        $AddDetailPotongProses->sisaPcs = $sisaPcs;
        $AddDetailPotongProses->hasilKg = $hasilKg;
        $AddDetailPotongProses->skb = $skb;
        $AddDetailPotongProses->bs = $bs;

        $AddDetailPotongProses->aKecil = $aKecil;        
        $AddDetailPotongProses->aKetek = $aKetek;        
        $AddDetailPotongProses->aKetekPotong = $aKetekPotong;        
        $AddDetailPotongProses->aSumbu = $aSumbu;        
        $AddDetailPotongProses->aBunder = $aBunder;        
        $AddDetailPotongProses->aTanggungKecil = $aTanggungKecil;        
        $AddDetailPotongProses->aTanggungBesar = $aTanggungBesar;        
        $AddDetailPotongProses->aTangan = $aTangan;        
        $AddDetailPotongProses->aKepalaPutih = $aKepalaPutih;        
        $AddDetailPotongProses->aKepalaBelang = $aKepalaBelang;        
        $AddDetailPotongProses->userId = $userId;        
        
        $AddDetailPotongProses->created_at = date('Y-m-d H:i:s');

        if ($AddDetailPotongProses->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
