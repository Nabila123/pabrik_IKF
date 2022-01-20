<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPurchase extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase';
    protected $fillable = ['jenisPurchase'];

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function findOne($id) {
        return self::where(array(
          'id'	=> $id,
        ))->first();
    }

    public static function purchaseKode()
    {
        $panjang = 5;
        $Huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $Angka = "1234567890";
        $kodeHuruf = '';
        $kodeAngka = '';
        $kode = '';

        for ($i = 0; $i < $panjang; $i++) {
            $kodeHuruf .= $Huruf[rand(0, strlen($Huruf) - 1)];
            $kodeAngka .= $Angka[rand(0, strlen($Angka) - 1)];
        }

        $kode = $kodeHuruf . "" . $kodeAngka;
        return $kode;
    }

    
}
