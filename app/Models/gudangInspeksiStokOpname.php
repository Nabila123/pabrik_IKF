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


    public static function createInspeksiProses($gdInspeksiKId, $gdDetailMaterialId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $tanggal, $userId){
        $addInspeksi = new gudangInspeksiStokOpname;
        $addInspeksi->gdInspeksiKId = $gdInspeksiKId;
        $addInspeksi->gdDetailMaterialId = $gdDetailMaterialId;
        $addInspeksi->purchaseId = $purchaseId;
        $addInspeksi->materialId = $materialId;
        $addInspeksi->jenisId = $jenisId;
        $addInspeksi->gramasi = $gramasi;
        $addInspeksi->diameter = $diameter;
        $addInspeksi->qty = 0;
        $addInspeksi->tanggal = $tanggal;

        $addInspeksi->userId = $userId;
        $addInspeksi->created_at = date('Y-m-d H:i:s');

        if ($addInspeksi->save()) {
            return $addInspeksi->id;
        } else {
            return 0;
        }
    }

}
