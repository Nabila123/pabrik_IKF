<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangRajutKeluar extends Model
{
    use HasFactory;

    protected $table = 'gd_rajutkeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function rajutKDetail()
    {
        return $this->hasOne('App\Models\GudangRajutKeluarDetail','id','gdRajutKId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $purchaseUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($purchaseUpdated);

        return 1;
    }
}
