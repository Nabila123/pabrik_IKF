<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangControlRejectDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_controlreject_detail';

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
        return $this->hasOne('App\Models\GudangControlReject','id','gdControlRejectId');
    }

    public static function updateStatusDiterima($id, $statusDiterima)
    {
        $inspeksiUpdated['statusDiterima'] = $statusDiterima;

        self::where('id', $id)->update($inspeksiUpdated);

        return 1;
    }
}
