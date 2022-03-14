<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekap extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitkeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function pegawai()
    {
        return $this->hasOne('App\Models\Pegawai','id','pegawaiId');
    }

    public static function JahitRekapCreate($posisi, $tanggal, $pegawaiId, $userId)
    {
        $addJahitRekap = New GudangJahitRekap;
        $addJahitRekap->posisi = $posisi;
        $addJahitRekap->tanggal = $tanggal;
        $addJahitRekap->pegawaiId = $pegawaiId;
        $addJahitRekap->userId = $userId;
        $addJahitRekap->created_at = date('Y-m-d H:i:s');

        if ($addJahitRekap->save()) {
            return $addJahitRekap->id;
        } else {
            return 0;
        }
    }
}
