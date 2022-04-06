<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiStokOpname extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadi_stokopname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function CreateGudangBarangStokOpname($gdBajuStokOpnameId, $kodeProduct, $purchaseId, $jenisBaju, $ukuranBaju)
    {
        $BarangJadiStokOpname = new GudangBarangJadiStokOpname;
        $BarangJadiStokOpname->tanggal = date('Y-m-d');
        $BarangJadiStokOpname->gdBajuStokOpnameId = $gdBajuStokOpnameId;
        $BarangJadiStokOpname->kodeProduct = $kodeProduct;
        $BarangJadiStokOpname->purchaseId = $purchaseId;
        $BarangJadiStokOpname->jenisBaju = $jenisBaju;
        $BarangJadiStokOpname->ukuranBaju = $ukuranBaju;

        $BarangJadiStokOpname->userId = \Auth::user()->id;
        $BarangJadiStokOpname->created_at = date('Y-m-d H:i:s');

        if ($BarangJadiStokOpname->save()) {
            return 1;
        } else {
            return 0;
        }
    }

}
