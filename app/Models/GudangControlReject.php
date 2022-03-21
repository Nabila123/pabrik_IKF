<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlReject extends Model
{
    use HasFactory;

    protected $table = 'gd_controlreject';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $jahitRejectUpdated['statusProses'] = $statusDiterima;

        self::where('id', $id)->update($jahitRejectUpdated);

        return 1;
    }
}
