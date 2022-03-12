<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekap extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitkeluar';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }
}
