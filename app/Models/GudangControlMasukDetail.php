<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_controlmasuk_detail';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createGudangControlMasukDetail($gdControlMId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $keluarDetail = new GudangControlMasukDetail;
        $keluarDetail->gdControlMId = $gdControlMId;
        $keluarDetail->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $keluarDetail->purchaseId = $purchaseId;
        $keluarDetail->jenisBaju = $jenisBaju;
        $keluarDetail->ukuranBaju = $ukuranBaju;
        $keluarDetail->created_at = date('Y-m-d H:i:s');

        if($keluarDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
