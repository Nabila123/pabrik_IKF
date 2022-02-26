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

    public static function gudangCuciUpdateField($fieldName, $updatedField, $id)
    {
        $gudangCuciUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($gudangCuciUpdated);

        if ($success) {
            return 1;
        }
    }
}