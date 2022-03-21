<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlRekap extends Model
{
    use HasFactory;

    protected $table = 'gd_controlrekap';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }    

    public static function BatilRekapCreate($userId)
    {
        $addBatilRekap = New GudangControlRekap;
        $addBatilRekap->tanggal = date('Y-m-d');
        $addBatilRekap->userId = $userId;
        $addBatilRekap->created_at = date('Y-m-d H:i:s');

        if ($addBatilRekap->save()) {
            return $addBatilRekap->id;
        } else {
            return 0;
        }
    }
}
