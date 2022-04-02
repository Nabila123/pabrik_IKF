<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadi_penjualan';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function kodeTransaksi() {
        $penjualan =  Self::select('kodeTransaksi')->get(); 
        $kode = date('Y-m-d')."".rand(10000, 99999);
        
        foreach ($penjualan as $value) {
            if ($value->kodePacking == $kode) {
                Self::kodePacking();
            }            
        }

        return $kode;
    }
    
    public Static function CreateBarangJadiPenjualan($kodeTransaksi, $customer, $tanggal, $totalHarga)
    {
        $BarangJadiPenjualan = new GudangBarangJadiPenjualan;
        $BarangJadiPenjualan->kodeTransaksi = $kodeTransaksi;
        $BarangJadiPenjualan->customer = $customer;
        $BarangJadiPenjualan->tanggal = $tanggal;
        $BarangJadiPenjualan->totalHarga = $totalHarga;

        $BarangJadiPenjualan->userId = \Auth::user()->id;
        $BarangJadiPenjualan->created_at = date('Y-m-d H:i:s');

        if ($BarangJadiPenjualan->save()) {
            return $BarangJadiPenjualan->id;
        } else {
            return 0;
        }
    }

    public static function PenjualanUpdateField($fieldName, $updatedField, $id)
    {
        $bajuFieldUpdated[$fieldName] = $updatedField;
        $success = self::where('id', $id)->update($bajuFieldUpdated);

        if ($success) {
            return 1;
        }
    }
}
