<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPackingRekap extends Model
{
    use HasFactory;
    protected $table = 'gd_packingrekap';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }

    public static function PackingRekapCreate($pegawaiId, $userId)
    {
        $addPackingRekap = New GudangPackingRekap;
        $addPackingRekap->pegawaiId = $pegawaiId;
        $addPackingRekap->tanggal = date('Y-m-d');
        $addPackingRekap->userId = $userId;
        $addPackingRekap->created_at = date('Y-m-d H:i:s');

        if ($addPackingRekap->save()) {
            return $addPackingRekap->id;
        } else {
            return 0;
        }
    }

}
