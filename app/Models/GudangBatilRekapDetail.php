<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBatilRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_batilrekap_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function rekap()
    {
        return $this->hasOne('App\Models\GudangBatilRekap','id','gdBatilRekapId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function operatorReq()
    {
        return $this->hasOne('App\Models\GudangBatilStokOpname','gdBajuStokOpnameId','gdBajuStokOpnameId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }
    
    public static function BatilRekapDetailCreate($gdBatilRekapId, $pegawaiId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $addBatilDetailRekap = New GudangBatilRekapDetail();
        $addBatilDetailRekap->gdBatilRekapId = $gdBatilRekapId;
        $addBatilDetailRekap->pegawaiId = $pegawaiId;
        $addBatilDetailRekap->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $addBatilDetailRekap->purchaseId = $purchaseId;
        $addBatilDetailRekap->jenisBaju = $jenisBaju;
        $addBatilDetailRekap->ukuranBaju = $ukuranBaju;
        $addBatilDetailRekap->created_at = date('Y-m-d H:i:s');

        if ($addBatilDetailRekap->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
