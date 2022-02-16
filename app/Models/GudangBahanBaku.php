<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'gd_bahanbaku';

    public function bahanBakuDetail()
    {
        return $this->hasOne('App\Models\GudangBahanBakuDetail','gudangId','id');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function updateFieldGudang($fieldName, $updatedField, $id)
    {
        $gudangFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($gudangFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
