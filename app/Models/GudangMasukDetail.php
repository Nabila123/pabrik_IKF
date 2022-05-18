<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'tr_gudang_masuk_detail';

    public function purchase()
    {
        return $this->hasOne('App\Models\AdminPurchase','id','purchaseId');
    }

    public static function createBarangKembaliDetail($gudangMasukId, $gudangStokId, $request)
    {
        $AddGudangMasukDetail = new GudangMasukDetail;
        $AddGudangMasukDetail->gudangMasukId = $gudangMasukId;
        $AddGudangMasukDetail->gudangStokId = $gudangStokId;
        $AddGudangMasukDetail->purchaseId = $request->purchaseId;
        $AddGudangMasukDetail->qty = $request->qty;
        $AddGudangMasukDetail->created_at = date('Y-m-d H:i:s');

        if ($AddGudangMasukDetail->save()) {
            return 1;
        } else {
            return 0;
        }
    }
}
