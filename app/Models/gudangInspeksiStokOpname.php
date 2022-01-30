<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gudangInspeksiStokOpname extends Model
{
    use HasFactory;

    protected $table = 'tr_inspeksi_stok_opname';

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


    public static function createInspeksiProses($gudangStokId, $purchaseId, $materialId, $jenisId, $tanggal, $userId){
        $addInspeksi = new gudangInspeksiStokOpname;
        $addInspeksi->gudangStokId = $gudangStokId;
        $addInspeksi->purchaseId = $purchaseId;
        $addInspeksi->materialId = $materialId;
        $addInspeksi->jenisId = $jenisId;
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
