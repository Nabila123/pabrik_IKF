<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSetrikaRekap extends Model
{
    use HasFactory;
    protected $table = 'gd_setrikarekap';

     public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }    

    public static function SetrikaRekapCreate($userId)
    {
        $addSetrikaRekap = New GudangSetrikaRekap;
        $addSetrikaRekap->tanggal = date('Y-m-d');
        $addSetrikaRekap->userId = $userId;
        $addSetrikaRekap->created_at = date('Y-m-d H:i:s');

        if ($addSetrikaRekap->save()) {
            return $addSetrikaRekap->id;
        } else {
            return 0;
        }
    }
}
