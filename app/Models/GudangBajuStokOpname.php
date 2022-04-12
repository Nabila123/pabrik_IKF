<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBajuStokOpname extends Model
{
    use HasFactory;

    protected $table = 'gd_baju_stok_opname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function BajuCreate($gdPotongProsesId, $purchaseId, $jenisBaju, $ukuranBaju, $soom, $bawahan, $jahit, $qty, $userId)
    {
        for ($i=0; $i < $qty; $i++) { 
            $AddBaju = new GudangBajuStokOpname;
            $AddBaju->gdPotongProsesId = $gdPotongProsesId;
            $AddBaju->purchaseId = $purchaseId;
            $AddBaju->jenisBaju = $jenisBaju;
            $AddBaju->ukuranBaju = $ukuranBaju;
            $AddBaju->soom = $soom;
            $AddBaju->bawahan = $bawahan;
            $AddBaju->jahit = $jahit;

            $AddBaju->userId = $userId;
            $AddBaju->created_at = date('Y-m-d H:i:s');

            $AddBaju->save();
        }        
        
        return 1;
    }

    public static function bajuUpdateField($fieldName, $updatedField, $id)
    {
        $bajuFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($bajuFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
