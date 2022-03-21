<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_controlrekap_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function rekap()
    {
        return $this->hasOne('App\Models\GudangControlRekap','id','gdControlRekapId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function operatorReq()
    {
        return $this->hasOne('App\Models\GudangControlStokOpname','gdBajuStokOpnameId','gdBajuStokOpnameId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }
    
    public static function ControlRekapDetailCreate($gdControlRekapId, $pegawaiId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $addControlDetailRekap = New GudangControlRekapDetail();
        $addControlDetailRekap->gdControlRekapId = $gdControlRekapId;
        $addControlDetailRekap->pegawaiId = $pegawaiId;
        $addControlDetailRekap->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $addControlDetailRekap->purchaseId = $purchaseId;
        $addControlDetailRekap->jenisBaju = $jenisBaju;
        $addControlDetailRekap->ukuranBaju = $ukuranBaju;
        $addControlDetailRekap->created_at = date('Y-m-d H:i:s');

        if ($addControlDetailRekap->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
