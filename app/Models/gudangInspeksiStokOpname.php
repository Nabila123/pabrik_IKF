<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudangInspeksiStokOpname extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksi_stok_opname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function gudangDetailmaterial()
    {
        return $this->hasOne('App\Models\GudangBahanBakuDetailMaterial','id','gdDetailMaterialId');
    }

    public function inspeksiKeluar()
    {
        return $this->hasOne('App\Models\GudangInspeksiKeluar','id','gdInspeksiKId');
    }

    public function inspeksiStokDetail()
    {
        return $this->hasOne('App\Models\gudangInspeksiStokOpnameDetail','gdInspeksiKId','id');
    }


    public static function createInspeksiProses($gdInspeksiKId, $gdDetailMaterialId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $qty, $tanggal, $userId){
        $addInspeksi = new gudangInspeksiStokOpname;
        $addInspeksi->gdInspeksiKId = $gdInspeksiKId;
        $addInspeksi->gdDetailMaterialId = $gdDetailMaterialId;
        $addInspeksi->purchaseId = $purchaseId;
        $addInspeksi->materialId = $materialId;
        $addInspeksi->jenisId = $jenisId;
        $addInspeksi->gramasi = $gramasi;
        $addInspeksi->diameter = $diameter;
        $addInspeksi->qty = $qty;
        $addInspeksi->tanggal = $tanggal;

        $addInspeksi->userId = $userId;
        $addInspeksi->created_at = date('Y-m-d H:i:s');

        if ($addInspeksi->save()) {
            return $addInspeksi->id;
        } else {
            return 0;
        }
    }

    public static function detailMaterialUpdateField($fieldName, $updatedField, $id)
    {
        $detailMaterialFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($detailMaterialFieldUpdated);

        if ($success) {
            return 1;
        }
    }

}
