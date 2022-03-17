<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRejectDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahit_reject_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function bajuOpname()
    {
        return $this->hasOne('App\Models\GudangBajuStokOpname','id','gdBajuStokOpnameId');
    }

    public function reject()
    {
        return $this->hasOne('App\Models\GudangJahitReject','id','gdJahitRejectId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
