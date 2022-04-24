<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangCuciKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_cucikeluar_detail';

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

    public static function createGudangCuciKeluarDetail($gdCuciKId, $gudangId, $gdDetailMaterialId, $purchaseId, $materialId, $gramasi, $diameter, $berat, $qty)
    {
        $keluarDetail = new GudangCuciKeluarDetail;
        $keluarDetail->gudangId = $gudangId;
        $keluarDetail->gdDetailMaterialId = $gdDetailMaterialId;
        $keluarDetail->gdCuciKId = $gdCuciKId;
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

    public static function gudangCuciUpdateField($fieldName, $updatedField, $id)
    {
        $gudangCuciUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($gudangCuciUpdated);

        if ($success) {
            return 1;
        }
    }

    function getKeluarIdAttribute() {
        return $this->gdCuciKId;
    }
}
