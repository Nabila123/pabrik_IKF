<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangPotongRequestDetail extends Model
{
    use HasFactory;

    protected $table = 'gd_potong_request_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public static function createGudangPotongRequestDetail($gdPotongReqId, $jenisBaju, $ukuranBaju, $qty)
    {
        $RequestDetail = new GudangPotongRequestDetail;
        $RequestDetail->gdPotongReqId = $gdPotongReqId;
        $RequestDetail->jenisBaju = $jenisBaju;
        $RequestDetail->ukuranBaju = $ukuranBaju;
        $RequestDetail->qty = $qty;
        $RequestDetail->created_at = date('Y-m-d H:i:s');

        if($RequestDetail->save()){
            return 1;
        }else{
            return 0;
        }
    }
}
