<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBatilStokOpname extends Model
{
    use HasFactory;

    protected $table = 'gd_batil_stokopname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function BatilStokOpnameCreate($gdBajuStokOpnameId, $tanggal, $purchaseId, $jenisBaju, $ukuranBaju, $statusBatil, $userId)
    {
        $AddBatilStokOpname = new GudangBatilStokOpname;
        $AddBatilStokOpname->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $AddBatilStokOpname->tanggal = $tanggal;
        $AddBatilStokOpname->purchaseId = $purchaseId;
        $AddBatilStokOpname->jenisBaju = $jenisBaju;
        $AddBatilStokOpname->ukuranBaju = $ukuranBaju;
        $AddBatilStokOpname->statusBatil = $statusBatil;

        $AddBatilStokOpname->userId = $userId;
        $AddBatilStokOpname->created_at = date('Y-m-d H:i:s');

        $AddBatilStokOpname->save();        
        
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
