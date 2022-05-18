<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangRajutMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_rajutmasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function rajutKeluar()
    {
        return $this->hasOne('App\Models\GudangRajutKeluar','id','gdRajutKId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $purchaseUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($purchaseUpdated);

        return 1;
    }

    public static function CreateRajutMasuk($gdRajutKId)
    {
        $addGudangRajutMasuk = new GudangRajutMasuk;
        $addGudangRajutMasuk->gdRajutKId = $gdRajutKId;
        $addGudangRajutMasuk->tanggal = date('Y-m-d H:i:s');
        $addGudangRajutMasuk->userId = \Auth::user()->id;
        $addGudangRajutMasuk->created_at = date('Y-m-d H:i:s');

        if ($addGudangRajutMasuk->save()) {
            return $addGudangRajutMasuk->id;
        } else {
            return 0;
        }
    }
}
