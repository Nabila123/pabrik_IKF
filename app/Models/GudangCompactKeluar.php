<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangCompactKeluar extends Model
{
    use HasFactory;

    protected $table = 'gd_compactkeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function CreateCompactKeluar($gdCuciKId)
    {
        $addGudangCompactKeluar = new GudangCompactKeluar;
        $addGudangCompactKeluar->gdCuciKId = $gdCuciKId;
        $addGudangCompactKeluar->tanggal = date('Y-m-d H:i:s');
        $addGudangCompactKeluar->userId = \Auth::user()->id;
        $addGudangCompactKeluar->created_at = date('Y-m-d H:i:s');

        if ($addGudangCompactKeluar->save()) {
            return $addGudangCompactKeluar->id;
        } else {
            return 0;
        }
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $purchaseUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($purchaseUpdated);

        return 1;
    }
}
