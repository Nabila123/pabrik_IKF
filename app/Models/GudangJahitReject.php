<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitReject extends Model
{
    use HasFactory;

    protected $table = 'gd_jahit_reject';

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
