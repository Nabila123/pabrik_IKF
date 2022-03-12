<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangJahitRekapDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_jahitkeluar_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }
}
