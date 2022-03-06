<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_potongkeluar_detail';

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

    public function gdPotongKeluar()
    {
        return $this->hasOne('App\Models\GudangPotongKeluar','id','gdPotongKId');
    }

    public static function createGudangPotongKeluarDetail($gdPotongKId, $gudangId, $gdDetailMaterialId, $gdInspeksiStokId, $purchaseId, $materialId, $gramasi, $diameter, $berat, $qty)
    {
        $keluarDetail = new GudangPotongKeluarDetail;
        $keluarDetail->gdPotongKId = $gdPotongKId;
        $keluarDetail->gudangId = $gudangId;
        $keluarDetail->gdDetailMaterialId = $gdDetailMaterialId;
        $keluarDetail->gdInspeksiStokId = $gdInspeksiStokId;
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
