<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangStokOpname extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_stok_opname';

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }
}
