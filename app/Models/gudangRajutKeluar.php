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

    public function gudang()
    {
        return $this->hasOne('App\Models\GudangBahanBaku','id','gudangId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function updateStatusDiterima($id, $gudangRequest, $statusDiterima)
    {
        $purchaseUpdated['statusDiterima'] = $statusDiterima;

        self::where('gudangRequest', $gudangRequest)->where('id', $id)->update($purchaseUpdated);

        return 1;
    }
}
