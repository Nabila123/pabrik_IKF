<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangInspeksiKeluarDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_inspeksikeluar_detail';

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
}
