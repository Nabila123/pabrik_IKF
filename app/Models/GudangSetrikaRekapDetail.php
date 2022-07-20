<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSetrikaRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_setrikarekap_detail';

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
    
    public static function SetrikaRekapDetailCreate($gdSetrikaRekapId, $pegawaiId, $gdBajuStokOpnameId, $purchaseId, $jenisBaju, $ukuranBaju, $keterangan)
    {
        $addSetrikaDetailRekap = New GudangSetrikaRekapDetail();
        $addSetrikaDetailRekap->gdSetrikaRekapId = $gdSetrikaRekapId;
        $addSetrikaDetailRekap->pegawaiId = $pegawaiId;
        $addSetrikaDetailRekap->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $addSetrikaDetailRekap->purchaseId = $purchaseId;
        $addSetrikaDetailRekap->jenisBaju = $jenisBaju;
        $addSetrikaDetailRekap->ukuranBaju = $ukuranBaju;
        $addSetrikaDetailRekap->keterangan = $keterangan;
        $addSetrikaDetailRekap->created_at = date('Y-m-d H:i:s');

        if ($addSetrikaDetailRekap->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
