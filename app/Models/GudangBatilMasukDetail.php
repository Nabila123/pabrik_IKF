<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBatilMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_batilmasuk_detail';

    public function potongProses()
    {
        return $this->hasOne('App\Models\GudangPotongProses','id','gdPotongProsesId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createGudangBatilMasukDetail($gdBatilMId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju, $qty)
    {
        $keluarDetail = new GudangBatilMasukDetail;
        $keluarDetail->gdBatilMId = $gdBatilMId;
        $keluarDetail->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $keluarDetail->purchaseId = $purchaseId;
        $keluarDetail->jenisBaju = $jenisBaju;
        $keluarDetail->ukuranBaju = $ukuranBaju;
        $keluarDetail->qty = $qty;
        $keluarDetail->created_at = date('Y-m-d H:i:s');

        if($keluarDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
