<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangCompactKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_compactkeluar_detail';

    public function gudang()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','id','gudangId');
    }

    public function compact()
    {
        return $this->hasOne('App\Models\GudangCompactKeluar','id','gdCompactKId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function CreateCompactKeluarDetail($gudangId, $gdDetailMaterialId, $gdCompactKId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $berat, $qty)
    {
        $addCompactKeluarDetail = new GudangCompactKeluarDetail;
        $addCompactKeluarDetail->gudangId = $gudangId;
        $addCompactKeluarDetail->gdDetailMaterialId = $gdDetailMaterialId;
        $addCompactKeluarDetail->gdCompactKId = $gdCompactKId;
        $addCompactKeluarDetail->purchaseId = $purchaseId;
        $addCompactKeluarDetail->materialId = $materialId;
        $addCompactKeluarDetail->jenisId = $jenisId;
        $addCompactKeluarDetail->gramasi = $gramasi;
        $addCompactKeluarDetail->diameter = $diameter;
        $addCompactKeluarDetail->berat = $berat;
        $addCompactKeluarDetail->qty = $qty;
        $addCompactKeluarDetail->userId = \Auth::user()->id;
        $addCompactKeluarDetail->created_at = date('Y-m-d H:i:s');

        if ($addCompactKeluarDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }

    function getKeluarIdAttribute() {
        return $this->gdCompactKId;
    }
}
