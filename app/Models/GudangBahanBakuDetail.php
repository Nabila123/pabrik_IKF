<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBakuDetail extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_bahan_baku_detail';

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }
}
