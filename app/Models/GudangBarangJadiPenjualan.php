<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadi_penjualan';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }
}
