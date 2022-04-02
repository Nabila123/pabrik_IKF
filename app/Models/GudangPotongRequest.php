<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongRequest extends Model
{
    use HasFactory;

    protected $table = 'gd_potong_request';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function potongReqDetail()
    {
        return $this->hasOne('App\Models\GudangPotongRequestDetail','gdPotongReqId','id');
    }

    public static function PotongRequestCreate($userId)
    {
        $addBatilRekap = New GudangPotongRequest;
        $addBatilRekap->tanggal = date('Y-m-d');
        $addBatilRekap->userId = $userId;
        $addBatilRekap->created_at = date('Y-m-d H:i:s');

        if ($addBatilRekap->save()) {
            return $addBatilRekap->id;
        } else {
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $InspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($InspeksiUpdated);

        return 1;
    }
}
