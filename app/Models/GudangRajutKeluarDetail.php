<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangRajutKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_rajutkeluar_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function gudang()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','id','gudangId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function createGudangRajutKeluarDetail($gdRajutKId, $gudangId, $purchaseId, $materialId, $qty)
    {
        $keluarDetail = new GudangRajutKeluarDetail;
        $keluarDetail->gdRajutKId = $gdRajutKId;
        $keluarDetail->gudangId = $gudangId;
        $keluarDetail->purchaseId = $purchaseId;
        $keluarDetail->materialId = $materialId;
        $keluarDetail->jenisId = $materialId;
        $keluarDetail->qty = $qty;
        $keluarDetail->created_at = date('Y-m-d H:i:s');

        if($keluarDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
