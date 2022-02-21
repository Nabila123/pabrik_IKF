<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangCompactMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_compactmasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreateCompactMasuk($gdCompactKId)
    {
        $addGudangCompactMasuk = new GudangCompactMasuk;
        $addGudangCompactMasuk->gdCompactKId = $gdCompactKId;
        $addGudangCompactMasuk->tanggal = date('Y-m-d H:i:s');
        $addGudangCompactMasuk->userId = \Auth::user()->id;
        $addGudangCompactMasuk->created_at = date('Y-m-d H:i:s');

        if ($addGudangCompactMasuk->save()) {
            return $addGudangCompactMasuk->id;
        } else {
            return 0;
        }
    }
}
