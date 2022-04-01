<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBarangJadiStokOpname extends Model
{
    use HasFactory;

    protected $table = 'gd_barangjadi_stokopname';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

}
