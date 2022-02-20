<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangRajutMasukDetail extends Model
{
    use HasFactory;
    protected $table = 'gd_rajutmasuk_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function CreateRajutMasukDetail($gudangId, $gdRajutMId, $purchaseId, $materialId, $jenisId, $gramasi, $diameter, $berat, $qty)
    {
        $addRajutMasukDetail = new GudangRajutMasukDetail;
        $addRajutMasukDetail->gudangId = $gudangId;
        $addRajutMasukDetail->gdRajutMId = $gdRajutMId;
        $addRajutMasukDetail->purchaseId = $purchaseId;
        $addRajutMasukDetail->materialId = $materialId;
        $addRajutMasukDetail->jenisId = $jenisId;
        $addRajutMasukDetail->gramasi = $gramasi;
        $addRajutMasukDetail->diameter = $diameter;
        $addRajutMasukDetail->berat = $berat;
        $addRajutMasukDetail->qty = $qty;
        $addRajutMasukDetail->userId = \Auth::user()->id;
        $addRajutMasukDetail->created_at = date('Y-m-d H:i:s');

        if ($addRajutMasukDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
