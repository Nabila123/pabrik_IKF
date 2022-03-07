<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitMasuk extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitmasuk';

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
    
}
