<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangInspeksiMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksimasuk_detail';

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

    public static function CreateInspeksiMasukDetail($gudangId, $gdDetailMaterialId, $gdInspeksiMId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $berat, $qty)
    {
        $addInspeksiMasukDetail = new GudangInspeksiMasukDetail;
        $addInspeksiMasukDetail->gudangId = $gudangId;
        $addInspeksiMasukDetail->gdDetailMaterialId = $gdDetailMaterialId;
        $addInspeksiMasukDetail->gdInspeksiMId = $gdInspeksiMId;
        $addInspeksiMasukDetail->purchaseId = $purchaseId;
        $addInspeksiMasukDetail->materialId = $materialId;
        $addInspeksiMasukDetail->jenisId = $jenisId;
        $addInspeksiMasukDetail->gramasi = $gramasi;
        $addInspeksiMasukDetail->diameter = $diameter;
        $addInspeksiMasukDetail->berat = $berat;
        $addInspeksiMasukDetail->qty = $qty;
        $addInspeksiMasukDetail->userId = \Auth::user()->id;
        $addInspeksiMasukDetail->created_at = date('Y-m-d H:i:s');

        if ($addInspeksiMasukDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
