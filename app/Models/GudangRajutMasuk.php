<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangRajutMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_rajutmasuk';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $purchaseUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($purchaseUpdated);

        return 1;
    }
}
