<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPICGudangRequestDetail extends Model
{
    use HasFactory;
    protected $table = 'ppic_gudangrequest_detail';

    public function user()
    {
        return $this->hasOne('App\Models\User','id','userId');
    }

    public function material()
    {
        return $this->hasOne('App\Models\MaterialModel','id','materialId');
    }

    public static function CreatePPICGudangRequestDetail($gdPpicRequestId, $materialId, $jenisId, $gramasi, $diameter, $qty)
    {
        $addPPICRequestDetail = new PPICGudangRequestDetail;
        $addPPICRequestDetail->gdPpicRequestId = $gdPpicRequestId;
        $addPPICRequestDetail->tanggal = date('Y-m-d H:i:s');
        $addPPICRequestDetail->materialId = $materialId;
        $addPPICRequestDetail->jenisId = $jenisId;
        $addPPICRequestDetail->gramasi = $gramasi;
        $addPPICRequestDetail->diameter = $gdPpicRequestId;
        $addPPICRequestDetail->qty = $qty;
        $addPPICRequestDetail->userId = \Auth::user()->id;
        $addPPICRequestDetail->created_at = date('Y-m-d H:i:s');

        if ($addPPICRequestDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }

}
