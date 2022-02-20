<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangBahanBakuDetailMaterial extends Model
{
    use HasFactory;

    protected $table = 'gd_bahanbaku_detail_material';

    public static function CreateDetailMaterial($gudangDetailId, $diameter, $gramasi, $brutto, $netto, $tarra, $unit, $unitPrice, $amount, $remark)
    {
        $addDetailMaterial = new GudangBahanBakuDetailMaterial;
        $addDetailMaterial->gudangDetailId = $gudangDetailId;
        $addDetailMaterial->diameter = $diameter;
        $addDetailMaterial->gramasi = $gramasi;
        $addDetailMaterial->brutto = $brutto;
        $addDetailMaterial->netto = $netto;
        $addDetailMaterial->tarra = $tarra;
        $addDetailMaterial->unit = $unit;
        $addDetailMaterial->unitPrice = $unitPrice;
        $addDetailMaterial->amount = $amount;
        $addDetailMaterial->remark = $remark;
        $addDetailMaterial->userId = \Auth::user()->id;
        $addDetailMaterial->created_at = date('Y-m-d H:i:s');

        if ($addDetailMaterial->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
