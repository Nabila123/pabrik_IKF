<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitkeluar_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function operatorReq()
    {
        return $this->hasOne('App\Models\GudangJahitRequestOperator','gdBajuStokOpnameId','gdBajuStokOpnameId');
    }

    public static function JahitRekapDetailCreate($gdJahitKId, $tanggal, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $addJahitDetailRekap = New GudangJahitRekapDetail();
        $addJahitDetailRekap->gdJahitKId = $gdJahitKId;
        $addJahitDetailRekap->tanggal = date('Y-m-d');
        $addJahitDetailRekap->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $addJahitDetailRekap->purchaseId = $purchaseId;
        $addJahitDetailRekap->jenisBaju = $jenisBaju;
        $addJahitDetailRekap->ukuranBaju = $ukuranBaju;
        $addJahitDetailRekap->created_at = date('Y-m-d H:i:s');

        if ($addJahitDetailRekap->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
