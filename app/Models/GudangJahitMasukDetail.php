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

    public static function createGudangInspeksiKeluarDetail($gdInspeksiKId, $gudangId, $gdDetailMaterialId, $purchaseId, $materialId, $gramasi, $diameter, $berat, $qty)
    {
        $keluarDetail = new GudangInspeksiKeluarDetail;
        $keluarDetail->gdInspeksiKId = $gdInspeksiKId;
        $keluarDetail->gudangId = $gudangId;
        $keluarDetail->gdDetailMaterialId = $gdDetailMaterialId;
        $keluarDetail->purchaseId = $purchaseId;
        $keluarDetail->materialId = $materialId;
        $keluarDetail->jenisId = $materialId;
        $keluarDetail->gramasi = $gramasi;
        $keluarDetail->diameter = $diameter;
        $keluarDetail->berat = $berat;
        $keluarDetail->qty = $qty;
        $keluarDetail->userId = \Auth::user()->id;
        $keluarDetail->created_at = date('Y-m-d H:i:s');

        if($keluarDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
