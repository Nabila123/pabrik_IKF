<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPurchase extends Model
{
    use HasFactory;

    protected $table = 'tr_purchase';
    protected $fillable = ['jenisPurchase'];

    public static function findOne($id) {
		return self::where(array(
			'id'	=> $id,
		))->first();
	}

    
}
