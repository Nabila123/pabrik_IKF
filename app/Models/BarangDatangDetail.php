<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangDatangDetail extends Model
{
    use HasFactory;
    protected $table = 'barang_datang_detail';

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function barangDatang()
    {
         return $this->belongsTo('App\Models\BarangDatang','barangDatangId','id');
    }

    public static function detailUpdateField($fieldName, $updatedField, $id)
    {
        $detailMaterialFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($detailMaterialFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}

