<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBatilMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_batilmasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function createBatilMasuk()
    {
       $addBatilMasuk = New GudangBatilMasuk;
       $addBatilMasuk->tanggal = date('Y-m-d');
       $addBatilMasuk->userId = \Auth::user()->id;
       $addBatilMasuk->created_at = date('Y-m-d H:i:s');

        if($addBatilMasuk->save()){
            return $addBatilMasuk->id;
        }else{
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusProses'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
