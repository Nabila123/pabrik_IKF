<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitrekap_detail';

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

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }
    
    public static function JahitRekapDetailCreate($gdJahitRekapId, $pegawaiId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $addJahitDetailRekap = New GudangJahitRekapDetail();
        $addJahitDetailRekap->gdJahitRekapId = $gdJahitRekapId;
        $addJahitDetailRekap->pegawaiId = $pegawaiId;
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
