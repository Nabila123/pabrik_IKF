<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangInspeksiKeluar extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksikeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $InspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($InspeksiUpdated);

        return 1;
    }
}
