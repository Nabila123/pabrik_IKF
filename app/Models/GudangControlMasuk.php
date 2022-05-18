<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_controlmasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function createControlMasuk()
    {
       $addControlMasuk = New GudangControlMasuk;
       $addControlMasuk->tanggal = date('Y-m-d');
       $addControlMasuk->userId = \Auth::user()->id;
       $addControlMasuk->created_at = date('Y-m-d H:i:s');

        if($addControlMasuk->save()){
            return $addControlMasuk->id;
        }else{
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
