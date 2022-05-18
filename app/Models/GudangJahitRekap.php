<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekap extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitrekap';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }    

    public static function JahitRekapCreate($posisi, $tanggal, $userId)
    {
        $addJahitRekap = New GudangJahitRekap;
        $addJahitRekap->posisi = $posisi;
        $addJahitRekap->tanggal = date('Y-m-d');
        $addJahitRekap->userId = $userId;
        $addJahitRekap->created_at = date('Y-m-d H:i:s');

        if ($addJahitRekap->save()) {
            return $addJahitRekap->id;
        } else {
            return 0;
        }
    }
}
