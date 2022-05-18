<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitmasuk_detail';

    public function potongProses()
    {
        return $this->hasOne('App\Models\GudangPotongProses','id','gdPotongProsesId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createGudangJahitMasukDetail($gdJahitMId, $gdPotongProsesId, $purchaseId, $jenisBaju, $ukuranBaju, $qty)
    {
        $keluarDetail = new GudangJahitMasukDetail;
        $keluarDetail->gdJahitMId = $gdJahitMId;
        $keluarDetail->gdPotongProsesId = $gdPotongProsesId;
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
