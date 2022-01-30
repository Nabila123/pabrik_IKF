<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_bahan_baku';

    public function bahanBakuDetail()
    {
        return $this->hasOne('App\Models\GudangBahanBakuDetail','gudangId','id');
    }
}
