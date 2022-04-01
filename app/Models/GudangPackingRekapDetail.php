<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPackingRekapDetail extends Model
{
    use HasFactory;
    protected $table = 'gd_packingrekap_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function rekap()
    {
        return $this->hasOne('App\Models\GudangPackingRekap','id','gdPackingRekapId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function operatorReq()
    {
        return $this->hasOne('App\Models\GudangSetrikaStokOpname','gdBajuStokOpnameId','gdBajuStokOpnameId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }

    public static function PackingRekapDetailCreate($gdPackingRekapId, $gdBajuStokOpnameId, $pegawaiId, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $addPackingDetailRekap = New GudangPackingRekapDetail();
        $addPackingDetailRekap->gdPackingRekapId = $gdPackingRekapId;
        $addPackingDetailRekap->pegawaiId = $pegawaiId;
        $addPackingDetailRekap->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $addPackingDetailRekap->purchaseId = $purchaseId;
        $addPackingDetailRekap->jenisBaju = $jenisBaju;
        $addPackingDetailRekap->ukuranBaju = $ukuranBaju;
        $addPackingDetailRekap->created_at = date('Y-m-d H:i:s');

        if ($addPackingDetailRekap->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
