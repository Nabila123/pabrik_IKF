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

    public static function purchaseDetailCreate($purchaseId, $materialId, $jumlah, $satuan, $harga, $totalHarga, $note)
    {
        $AddDetailPurchase = new AdminPurchaseDetail;
        $AddDetailPurchase->purchaseId = $purchaseId;
        $AddDetailPurchase->materialId = $materialId;
        $AddDetailPurchase->qty = $jumlah;
        $AddDetailPurchase->unit = $satuan;
        $AddDetailPurchase->unitPrice = $harga;
        $AddDetailPurchase->amount = $totalHarga;
        $AddDetailPurchase->remark = $note;
        $AddDetailPurchase->created_at = date('Y-m-d H:i:s');

        if ($AddDetailPurchase->save()) {
            return 1;
        } else {
            return 0;
        }
        
    }
}
