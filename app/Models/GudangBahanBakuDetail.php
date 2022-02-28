<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBakuDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_bahanbaku_detail';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }
    
    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function bahanBakuDetailMaterial()
    {
        return $this->hasMany('App\Models\GudangBahanBakuDetailMaterial','gudangDetailId','id');
    }

    public static function CreateBahanBakuDetail($gudangId, $purchaseId, $materialId, $qtyPermintaan, $qtySaatIni)
    {
        $addBahanBakuDetail = new GudangBahanBakuDetail;
        $addBahanBakuDetail->gudangId = $gudangId;
        $addBahanBakuDetail->purchaseId = $purchaseId;
        $addBahanBakuDetail->materialId = $materialId;
        $addBahanBakuDetail->qtyPermintaan = $qtyPermintaan;
        $addBahanBakuDetail->qtySaatIni = $qtySaatIni;
        $addBahanBakuDetail->userId = \Auth::user()->id;
        $addBahanBakuDetail->created_at = date('Y-m-d H:i:s');

        if ($addBahanBakuDetail->save()) {
            return $addBahanBakuDetail->id;
        } else {
            return 0;
        }
    }
}
