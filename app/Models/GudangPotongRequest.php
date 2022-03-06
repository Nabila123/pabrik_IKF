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

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $InspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($InspeksiUpdated);

        return 1;
    }
}
