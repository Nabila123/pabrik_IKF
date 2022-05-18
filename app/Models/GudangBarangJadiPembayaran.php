<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadi_pembayaran';

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
    
    public Static function CreateBarangJadiPembayaran($kodeTransaksi, $customer, $tanggal, $totalHarga)
    {
        $BarangJadiPembayaran = new GudangBarangJadiPembayaran;
        $BarangJadiPembayaran->kodeTransaksi = $kodeTransaksi;
        $BarangJadiPembayaran->customer = $customer;
        $BarangJadiPembayaran->tanggal = $tanggal;
        $BarangJadiPembayaran->totalHarga = $totalHarga;

        $BarangJadiPembayaran->userId = \Auth::user()->id;
        $BarangJadiPembayaran->created_at = date('Y-m-d H:i:s');

        if ($BarangJadiPembayaran->save()) {
            return 1;
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
