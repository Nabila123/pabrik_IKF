<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPurchaseDetail extends Model
{
    protected $table = 'tr_purchase_detail';

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }
}
