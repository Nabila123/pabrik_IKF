<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BarangDatang extends Model
{
    use HasFactory;

    protected $table = 'barang_datang';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\barangDatangDetail','barangDatangId','id');
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
