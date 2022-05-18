<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangInspeksiKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksikeluar_detail';

    public function gudang()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','id','gudangId');
    }

    public function inspeksi()
    {
        return $this->hasOne('App\Models\GudangInspeksiKeluar','id','gdInspeksiKId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
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


    function getKeluarIdAttribute() {
        return $this->gdInspeksiKId;
    }
}
