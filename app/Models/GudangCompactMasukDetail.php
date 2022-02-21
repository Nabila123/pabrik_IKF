<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangCompactMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_compactmasuk_detail';

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

    public static function CreateCompactMasukDetail($gudangId, $gdCompactMId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $berat, $qty)
    {
        $addCompactMasukDetail = new GudangCompactMasukDetail;
        $addCompactMasukDetail->gudangId = $gudangId;
        $addCompactMasukDetail->gdCompactMId = $gdCompactMId;
        $addCompactMasukDetail->purchaseId = $purchaseId;
        $addCompactMasukDetail->materialId = $materialId;
        $addCompactMasukDetail->jenisId = $jenisId;
        $addCompactMasukDetail->gramasi = $gramasi;
        $addCompactMasukDetail->diameter = $diameter;
        $addCompactMasukDetail->berat = $berat;
        $addCompactMasukDetail->qty = $qty;
        $addCompactMasukDetail->userId = \Auth::user()->id;
        $addCompactMasukDetail->created_at = date('Y-m-d H:i:s');

        if ($addCompactMasukDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
