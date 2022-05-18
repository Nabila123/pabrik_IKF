<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangInspeksiMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksimasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }

    public static function CreateInspeksiMasuk($gdInspeksiKId)
    {
        $addGudangInspeksiMasuk = new GudangInspeksiMasuk;
        $addGudangInspeksiMasuk->gdInspeksiKId = $gdInspeksiKId;
        $addGudangInspeksiMasuk->tanggal = date('Y-m-d H:i:s');
        $addGudangInspeksiMasuk->userId = \Auth::user()->id;
        $addGudangInspeksiMasuk->created_at = date('Y-m-d H:i:s');

        if ($addGudangInspeksiMasuk->save()) {
            return $addGudangInspeksiMasuk->id;
        } else {
            return 0;
        }
    }
}
