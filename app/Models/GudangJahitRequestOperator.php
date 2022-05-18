<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRequestOperator extends Model
{
    use HasFactory;

    protected $table = 'gd_jahit_request';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function OperatorBajuCreate($gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju, $soom, $bawahan, $jahit, $userId)
    {
            $AddBaju = new GudangJahitRequestOperator;
            $AddBaju->gdBajuStokOpnameId = $gdBajuStokOpnameId;
            $AddBaju->purchaseId = $purchaseId;
            $AddBaju->jenisBaju = $jenisBaju;
            $AddBaju->ukuranBaju = $ukuranBaju;
            $AddBaju->soom = $soom;
            $AddBaju->bawahan = $bawahan;
            $AddBaju->jahit = $jahit;

            $AddBaju->userId = $userId;
            $AddBaju->created_at = date('Y-m-d H:i:s');

            $AddBaju->save();
        
        return 1;
    }

    public static function GudangOperatorBajuUpdateField($fieldName, $updatedField, $id)
    {
        $GudangOperatorBajuFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($GudangOperatorBajuFieldUpdated);

        if ($success) {
            return 1;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
