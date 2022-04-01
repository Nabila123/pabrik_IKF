<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiPenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadipenjualan_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }
}
