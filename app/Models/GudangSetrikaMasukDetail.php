<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSetrikaMasukDetail extends Model
{
    use HasFactory;
    protected $table = 'gd_setrikamasuk_detail';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createGudangSetrikaMasukDetail($gdSetrikaMId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $masukDetail = new GudangSetrikaMasukDetail;
        $masukDetail->gdControlMId = $gdSetrikaMId;
        $masukDetail->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $masukDetail->purchaseId = $purchaseId;
        $masukDetail->jenisBaju = $jenisBaju;
        $masukDetail->ukuranBaju = $ukuranBaju;
        $masukDetail->created_at = date('Y-m-d H:i:s');

        if($masukDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
