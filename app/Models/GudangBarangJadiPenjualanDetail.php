<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiPenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadipenjualan_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public Static function CreateBarangJadiPenjualanDetail($barangJadiPenjualanId, $kodeProduct, $purchaseId, $jenisBaju, $ukuranBaju, $qty, $harga)
    {
        $BarangJadiPenjualan = new GudangBarangJadiPenjualanDetail;
        $BarangJadiPenjualan->barangJadiPenjualanId = $barangJadiPenjualanId;
        $BarangJadiPenjualan->kodeProduct = $kodeProduct;
        $BarangJadiPenjualan->purchaseId = $purchaseId;
        $BarangJadiPenjualan->jenisBaju = $jenisBaju;
        $BarangJadiPenjualan->ukuranBaju = $ukuranBaju;
        $BarangJadiPenjualan->qty = $qty;
        $BarangJadiPenjualan->harga = $harga;

        $BarangJadiPenjualan->userId = \Auth::user()->id;
        $BarangJadiPenjualan->created_at = date('Y-m-d H:i:s');

        if ($BarangJadiPenjualan->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
